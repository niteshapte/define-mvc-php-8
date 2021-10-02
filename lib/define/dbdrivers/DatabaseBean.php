<?php
declare(strict_types=1);
namespace Define\DBDrivers;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * DATABASE BEAN
 *
 * Contains the database connection information
 *
 * @category Define
 * @package DBDrivers
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2015-2021 Nitesh Apte
 * @version 2.0.0
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
class DatabaseBean {

    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $port;

    /**
     * @var string
     */
    private string $dbType;

    /**
     * @var string
     */
    private string $schema;

    /**
     * @var string
     */
    private string $dbName;

    /**
     * @var string
     */
    private string $user;

    /**
     * @var string
     */
    private string $dbPass;

    public function __construct(string $dbType, string $host, string $user, string $dbPass, string $dbName, string $port, string $schema) {
        $this->setDbType($dbType);
        $this->setHost($host);
        $this->setUser($user);
        $this->setDbPass($dbPass);
        $this->setDbName($dbName);
        $this->setPort($port);
        $this->setSchema($schema);
    }

    /**
     * @return string
     */
    public function getHost(): string {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getPort(): string {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort(string $port): void {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getDbType(): string {
        return $this->dbType;
    }

    /**
     * @param string $dbType
     */
    public function setDbType(string $dbType): void {
        $this->dbType = $dbType;
    }

    /**
     * @return string
     */
    public function getSchema(): string {
        return $this->schema;
    }

    /**
     * @param string $schema
     */
    public function setSchema(string $schema): void {
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function getDbName(): string {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     */
    public function setDbName(string $dbName): void {
        $this->dbName = $dbName;
    }

    /**
     * @return string
     */
    public function getDbPass(): string {
        return $this->dbPass;
    }

    /**
     * @param string $dbPass
     */
    public function setDbPass(string $dbPass): void {
        $this->dbPass = $dbPass;
    }

    /**
     * @return string
     */
    public function getUser(): string {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void {
        $this->user = $user;
    }
}