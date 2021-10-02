<?php
declare(strict_types=1);
namespace Define\DBDrivers;

use Define\Exceptions\FrameworkException;
use Define\Traits\SingletonTrait;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * SQLITE DRIVER
 *
 * Sqlite Driver class
 *
 * @category Define
 * @package DBDrivers
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2015-2021 Nitesh Apte
 * @version 1.1.0
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
class SQLiteDBDriver implements IDatabase {

    use SingletonTrait;

    /**
     * @var mixed $conn
     */
    private mixed $conn;

    /**
     * @var mixed
     */
    private mixed $sqlExec;

    /**
     * Open a connection to MySQL server
     *
     * @param DatabaseBean $bean
     * @return IDatabase
     * @throws FrameworkException
     */
    public function getConnection(DatabaseBean $bean) : IDatabase {
        $sqliteError = '';
        $this->conn = sqlite_open($bean->getDbName(), 0666, $sqliteError);
        if($sqliteError != '') {
            throw new FrameworkException("Unable to connect to Sqlite database.");
        }
        return $this;
    }

    /**
     * Execute the SQL
     *
     * @param String $query
     * @param array $parameter
     * @return IDatabase
     */
    public function executeSql(string $query, array $parameter = array()) : IDatabase {
        $this->sqlExec = sqlite_query($this->conn, $query);
        return $this;
    }

    /**
     * Starts a transaction
     *
     * @throws FrameworkException
     */
    public function beginTransaction() : IDatabase {
        throw new FrameworkException('Transaction is not supported in Sqlite. Hence, transaction initialization quited.');
    }

    /**
     * Commit the changes
     *
     * @return IDatabase
     * @throws FrameworkException
     */
    public function commitTransaction() : IDatabase {
        throw new FrameworkException('Transaction is not supported in Sqlite. Hence, transaction commit quited.');
    }

    /**
     * Rollback the changes
     *
     * @return IDatabase
     * @throws FrameworkException
     */
    public function rollbackTransaction() : IDatabase {
        throw new FrameworkException('Transaction is not supported in Sqlite. Hence, transaction rollback quited.');
    }

    /**
     * Fetch a result row as an associative array
     *
     * @return array
     */
    public function fetchAssoc() : array {
        $sqlStoreValues = array();
        while($rows = sqlite_fetch_array($this->sqlExec, SQLITE_ASSOC)) {
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
        while($row = sqlite_fetch_array($this->sqlExec, SQLITE_BOTH)) {
            $sqlStoreValues[] = $row;
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
        while($rows = sqlite_fetch_object($this->conn)) {
            $sqlStoreValues[] = $rows;
        }
        $this->freeResult();
        return $sqlStoreValues;
    }

    /**
     * Fetch number of affected rows in previous MySQL operation
     *
     * @return int
     */
    public function affectedRows() : int {
        return sqlite_num_rows($this->sqlExec);
    }

    /**
     * Method to return the id of last affected row
     *
     * @return Int $this->lastID Last id
     */
    public function lastID() : int {
        return sqlite_last_insert_rowid($this->sqlExec);
    }

    /**
     * Method to return id of last multiple insert statements executed
     *
     * @param Int $size Count of statements
     * @return array
     */
    public function multipleID(int $size) : array {
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
        unset($this->sqlExec);
    }

    /**
     * Destroy SQLite connection
     *
     * @return void
     */
    public function __destruct() {
        sqlite_close($this->conn);
    }

    /**
     * @inheritDoc
     */
    public function sqlErrorNo(): int {
        return sqlite_last_error($this->conn);
    }
}