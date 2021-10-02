<?php
declare(strict_types=1);
namespace Define\DBDrivers;

use Define\Exceptions\FrameworkException;
use Define\Traits\SingletonTrait;
use JetBrains\PhpStorm\Pure;
use mysqli;
use mysqli_result;
use mysqli_stmt;
use function phpversion;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * MYSQLI DRIVER
 *
 * MySQLi Driver class
 *
 * @category Define
 * @package DBDrivers
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2015 Nitesh Apte
 * @version 1.0.0
 * @since 1.0.0
 * @license https://www.gnu.org/licenses/gpl.txt GNU General Public License v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class MySQLiDBDriver implements IDatabase {

    use SingletonTrait;

    private ?mysqli $conn = null;

    private ?mysqli_stmt $preparedStatement = null;

    private ?mysqli_result $result = null;

    /**
     * Open a connection to MySQLi server
     *
     * @param DatabaseBean $bean
     * @return MySQLiDBDriver
     */
    public function getConnection(DatabaseBean $bean) : MySQLiDBDriver {
        $this->conn = new mysqli($bean->getHost(), $bean->getUser(), $bean->getDbPass(), $bean->getDbName());
        return $this;
    }

    /**
     * Execute the SQL
     *
     * @param String $query
     * @param array $parameter
     * @return IDatabase
     * @throws FrameworkException
     */
    public function executeSql(string $query, array $parameter = array()) : IDatabase {
        if (!is_string($query) || empty($query)):
            throw new FrameworkException("The specified query is not valid.");
        endif;
        $this->preparedStatement = $this->conn->prepare($query);
        if($this->preparedStatement === false):
            throw new FrameworkException("Prepared Statement is wrong. Error: ".$this->sqlErrorNo(), $this->sqlErrorNo());
        endif;
        if(!empty($parameter)):
            $args = $this->castValues($parameter);
            call_user_func_array(array($this->preparedStatement, 'bind_param'), $this->makeValuesReferenced($args));
        endif;
        $this->preparedStatement->execute();
        return $this;
    }

    /**
     * @param array|null $parameter
     * @return array
     */
    private function castValues(array $parameter = null) : array {
        $types = '';
        if (!empty($parameter)) {
            foreach($parameter as $v) {
                $types .= match ($v) {
                    '', is_string($v) => 's',
                    is_null($v), is_bool($v), is_int($v) => 'i',
                    is_float($v) => 'd',
                };
            }
        }
        return array_merge(array($types), $parameter);
    }

    /**
     * @param array $arr
     * @return array
     */
    private function makeValuesReferenced(array $arr) : array {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$value;

        return $refs;
    }

    /**
     * Starts a transaction
     *
     * @return IDatabase
     */
    public function beginTransaction() : IDatabase {
        phpversion() > "5.5.0" ? $this->conn->begin_transaction() : $this->conn->autocommit(false);
        return $this;
    }

    /**
     * Commit the changes
     *
     * @return IDatabase
     */
    public function commitTransaction() : IDatabase {
        phpversion() > "5.5.0" ? $this->conn->commit() : $this->conn->autocommit(true);
        return $this;
    }

    /**
     * Rollback the changes
     *
     * @return IDatabase
     */
    public function rollbackTransaction() : IDatabase {
        $this->conn->rollback();
        return $this;
    }

    /**
     * Fetch a result row as an associative array
     *
     * @return array
     */
    public function fetchAssoc() : array {
        $sqlStoreValues = array();
        $this->result = $this->preparedStatement->get_result();
        while($rows = $this->result->fetch_assoc()){
            $sqlStoreValues[] = $rows;
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch a result row as an associative array and as an enumerated array
     *
     * @return array
     */
    public function fetchArray() : array {
        $sqlStoreValues = array();
        $this->result = $this->preparedStatement->get_result();
        while($rows = $this->result->fetch_array(MYSQLI_BOTH)){
            $sqlStoreValues[] = $rows;
        }

        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch a result row as an object
     *
     * @param object|null $object
     * @return array
     */
    public function fetchObject(object $object = null) : array {
        $sqlStoreValues = array();
        $this->result = $this->preparedStatement->get_result();
        while($rows = $this->result->fetch_object($object)){
            $sqlStoreValues[] = $rows;
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch number of affected rows in previous mysqli operation
     *
     * @return int
     */
    public function affectedRows() : int {
        return $this->conn->affected_rows;
    }

    public function isUpdated() : bool {
        return mysqli_sqlstate($this->conn) == 00000;
    }

    /**
     * Method to return the id of last affected row
     *
     * @return Int $this->lastID Last id
     */
    public function lastID() : int {
        return $this->conn->insert_id;
    }

    /**
     * Method to return id of last multiple insert statements executed
     *
     * @param Int $size Count of statements
     * @return array
     */
    #[Pure] public function multipleID(int $size) : array {
        $lastId = $this->lastID();
        $lastIDs = array();
        for($i = $lastId; $i< ($lastId + $size); $i++){
            $lastIDs[] = $i;
        }
        return $lastIDs;
    }

    /**
     * Method to free the results from memory
     *
     * @return void
     */
    public function freeResult() : void {
        $this->result->close();
        $this->preparedStatement->free_result();
        $this->preparedStatement->close();

    }

    /**
     * Print the mysqli error number
     *
     * @return int
     */
    public function sqlErrorNo() : int {
        return $this->conn->errno;
    }

    /**
     * Destroy mysqli connection
     *
     * @return void
     */
    public function __destruct() {
        $this->conn->close();
    }
}