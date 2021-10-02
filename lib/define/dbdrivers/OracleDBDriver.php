<?php
declare(strict_types=1);
namespace Define\DBDrivers;

use Define\Exceptions\CloneNotSupportedException;
use Define\Exceptions\FrameworkException;
use Define\Exceptions\NotSerializableException;
use Define\Traits\SingletonTrait;
use function oci_connect;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * ORACLE DRIVER
 *
 * Oracle Driver class
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
class OracleDBDriver implements IDatabase {

    use SingletonTrait;

    /**
     * @var mixed $conn
     */
    private mixed $conn;

    /**
     * @var array
     */
    private array $sqlRows = array();

    /**
     * @var mixed
     */
    private array $sqlExec;

    /**
     * Open a connection to Oracle server
     *
     * @param DatabaseBean $bean
     * @return IDatabase $this
     */
    public function getConnection(DatabaseBean $bean) : IDatabase {
        $this->conn = oci_connect($bean->getUser(), $bean->getDbPass(), $bean->getHost().'/XE');
        return $this;
    }

    /**
     * Execute the SQL
     *
     * @param String $query
     * @param array $parameter
     * @return IDatabase $this
     */
    public function executeSql(string $query, array $parameter = array()) : IDatabase {
        if (isset($this)) {
            $this->sqlExec = oci_parse($this->conn, $query);
        }
        oci_execute($this->sqlExec);
        return $this;
    }

    /**
     * Starts a transaction
     *
     * @return IDatabase $this Current object
     */
    public function beginTransaction() : IDatabase {
        return $this;
    }

    /**
     * Commit the changes
     *
     * @return IDatabase $this Current object
     */
    public function commitTransaction() : IDatabase {
        oci_commit($this->conn);
        return $this;
    }

    /**
     * Rollback the changes
     *
     * @return IDatabase $this Current object
     */
    public function rollbackTransaction() : IDatabase {
        oci_rollback($this->conn);
        return $this;
    }

    /**
     * Fetch a result row as an associative array
     *
     * @return array $this
     */
    public function fetchAssoc() : array {
        $sqlStoreValues = array();
        while($this->sqlRows = oci_fetch_assoc($this->sqlExec)) {
            $sqlStoreValues[] = $this->sqlRows;
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch a result row as an associative array and as an enumerated array
     *
     * @return array $this->sqlStoreValues
     */
    public function fetchArray() : array {
        $sqlStoreValues = array();
        while($this->sqlRows = oci_fetch_array($this->sqlExec, OCI_BOTH)) {
            $sqlStoreValues[] = $this->sqlRows;
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch a result row as an object
     *
     * @return array $sqlStoreValues
     */
    public function fetchObject() : array {
        $sqlStoreValues = array();
        if (isset($this)) {
            while($this->sqlRows = oci_fetch_object($this->sqlExec, OCI_BOTH)){
                $sqlStoreValues[] = $this->sqlRows;
            }
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch number of affected rows in previous Oracle operation
     *
     * @return int $this
     */
    public function affectedRows() : int {
        return oci_num_rows($this->sqlExec);
    }

    /**
     * Method to return the id of last affected row
     *
     * @param string $tableName
     * @param string $fieldName
     * @return Int $this->lastID Last id
     */
    public function lastID(string $tableName = '', string $fieldName = '') : int {
        $oracleSql = "SELECT $fieldName FROM $tableName ORDER BY $fieldName DESC LIMIT 1";

        $this->executeSql($oracleSql);
        return $this->sqlRows[$fieldName];
    }

    /**
     * Method to return id of last multiple insert statements executed
     *
     * @param Int $size Count of statements
     * @param string? $tableName
     * @param string? $fieldName
     * @return array
     */
    public function multipleID(int $size, string $tableName = '', string $fieldName = '') : array {
        $lastId = $this->lastID($tableName, $fieldName);

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
        oci_free_statement($this->sqlExec);
    }

    /**
     * Destroy Oracle connection
     *
     * @return void
     */
    public function __destruct() {
        oci_close($this->conn);
    }

    /**
     * @inheritDoc
     */
    public function sqlErrorNo(): int {
        return oci_error($this->conn)['code'];
    }
}