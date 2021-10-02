<?php
declare(strict_types=1);
namespace Define\DBDrivers;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * IDATABASE
 *
 * Interface declaring the methods that to be followed for database interaction.
 *
 * @category Define
 * @package DBDrivers
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2015-2021 Nitesh Apte
 * @version 3.0.0
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
interface IDatabase {

    /**
     * Method for connecting to database
     *
     * @param DatabaseBean $bean
     * @return void
     */
    public function getConnection(DatabaseBean $bean);

    /**
     * Execute a sql query
     *
     * @param String $query
     * @param array $parameter
     * @return IDatabase
     */
    public function executeSql(string $query, array $parameter = array()) : IDatabase;

    /**
     * Begin the transaction
     *
     * @return IDatabase
     */
    public function beginTransaction(): IDatabase;

    /**
     * Commit the transaction
     *
     * @return IDatabase
     */
    public function commitTransaction() : IDatabase;

    /**
     * Rolls back the transaction
     *
     * @return IDatabase
     */
    public function rollbackTransaction() : IDatabase;

    /**
     * Fetch associative array
     *
     * @return array
     */
    public function fetchAssoc() : array;

    /**
     * Fetch enumerated array
     *
     * @return array
     */
    public function fetchArray() : array;

    /**
     * Fetch Object instead of array
     *
     * @return array
     */
    public function fetchObject() : array;

    /**
     * Fetch the number of affected rows
     *
     * @return int number of rows
     */
    public function affectedRows() : int;

    /**
     * Fetch the last inserted id
     *
     * @return int last row id of table
     */
    public function lastID() : int;

    /**
     * Fetch the ids of last entry
     *
     * @param int $size
     */
    public function multipleID(int $size) : array;

    /**
     * Frees the database result
     *
     * @return void
     */
    public function freeResult() : void;

    /**
     * Print the error number
     *
     * @return number
     */
    public function sqlErrorNo() : int;
}