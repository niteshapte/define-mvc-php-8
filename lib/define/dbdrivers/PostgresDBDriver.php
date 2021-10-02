<?php
declare(strict_types=1);
namespace Define\DBDrivers;

use Define\Traits\SingletonTrait;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * POSTGRE DRIVER
 *
 * Postgre Driver class
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
class PostgresDBDriver implements IDatabase {

    use SingletonTrait;

    /**
     * @var string|bool $conn
     */
    private string|bool $conn;

    /**
     * @var array
     */
    private array $sqlRows = array();

    /**
     * @var mixed
     */
    private mixed $sqlExec;

    /**
     * Open a connection to Postgre server
     *
     * @param DatabaseBean $bean
     * @return IDatabase
     */
    public function getConnection(DatabaseBean $bean) : IDatabase {
        $this->conn = pg_connect("host={$bean->getHost()} port={$bean->getPort()} dbname={$bean->getSchema()} user={$bean->getDbName()} password={$bean->getDbPass()}");
        return $this;
    }

    /**
     * Select a Oracle database.
     *
     * @return void
     */
    public function selectDB() : void {
        // Nothing to do here
    }

    /**
     * Execute the SQL
     *
     * @param String $query
     * @param array $parameter
     * @return IDatabase
     */
    public function executeSql(string $query, array $parameter = array()) : IDatabase {
        $this->sqlExec = pg_execute($this->conn, $query, $parameter);
        return $this;
    }

    /**
     * Starts a transaction
     *
     * @return IDatabase
     */
    public function beginTransaction() : IDatabase {
        pg_query($this->conn, "BEGIN");
        return $this;
    }

    /**
     * Commit the changes
     *
     * @return IDatabase
     */
    public function commitTransaction() : IDatabase {
        pg_query($this->conn, "COMMIT");
        return $this;
    }

    /**
     * Rollback the changes
     *
     * @return IDatabase
     */
    public function rollbackTransaction() : IDatabase {
        pg_query($this->conn, "ROLLBACK");
        return $this;
    }

    /**
     * Fetch a result row as an associative array
     *
     * @return array
     */
    public function fetchAssoc() : array {
        $sqlStoreValues = array();
        while($rows = pg_fetch_assoc($this->sqlExec)) {
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
        while($rows = pg_fetch_array($this->sqlExec, 1, PGSQL_BOTH)) {
            $sqlStoreValues[] = $rows;
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch a result row as an object
     *
     * @return array
     */
    public function fetchObject() : array {
        $sqlStoreValues = array();
        while($rows = pg_fetch_object($this->sqlExec)) {
            $sqlStoreValues[] = $rows;
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch number of affected rows in previous Oracle operation
     *
     * @return int
     */
    public function affectedRows() : int {
        return pg_affected_rows($this->sqlExec);
    }

    /**
     * Method to return the id of last affected row
     *
     * @param string $tableName
     * @param string $fieldName
     * @return int
     */
    public function lastID(string $tableName = '', string $fieldName = '') : int {
        $pgSql = "SELECT $fieldName FROM $tableName ORDER BY $fieldName DESC LIMIT 1";

        $this->executeSql($pgSql);
        return $this->sqlRows[$fieldName];
    }

    /**
     * Method to return id of last multiple insert statements executed
     *
     * @param int $size Count of statements
     * @param string $tableName
     * @param string $fieldName
     * @return array
     */
    public function multipleID(int $size, string $tableName = '', string $fieldName = '') : array {
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
        pg_free_result($this->sqlExec);
    }

    public function __destruct() {
        pg_close($this->conn);
    }

    /**
     * @inheritDoc
     */
    public function sqlErrorNo(): int {
        return (int)pg_result_error_field($this->sqlExec, PGSQL_DIAG_SQLSTATE);
    }
}