<?php
declare(strict_types=1);
namespace Define\DBDrivers;

use Define\Traits\SingletonTrait;
use PDO;
use PDOStatement;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

class PDODBDriver implements IDatabase {

    use SingletonTrait;

    /**
     * @var PDO
     */
    private PDO $pdoObject;

    /**
     * @var PDOStatement
     */
    private PDOStatement $prepareStatement;

    /**
     * Method for connecting to database
     *
     * @param DatabaseBean $bean
     * @return IDatabase
     */
    public function getConnection(DatabaseBean $bean) : IDatabase {
        $this->pdoObject = new PDO($bean->getDbType().":host=".$bean->getHost().";dbname=".$bean->getDbName().";charset=utf8", $bean->getUser(), $bean->getDbPass(), array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return $this;
    }

    /**
     * Execute a sql query
     *
     * @param String $query
     * @param array $parameter
     * @return IDatabase
     */
    public function executeSql(string $query, array $parameter = array()) : IDatabase {
        $this->prepareStatement = $this->pdoObject->prepare($query);
        for($i = 0; $i < sizeof($parameter); $i++) {
            $this->prepareStatement->bindParam(":x". $i+1, $parameter[$i]);
        }
        $this->prepareStatement->execute();
        return $this;
    }

    /**
     * Begin the transaction
     *
     * @return IDatabase
     */
    public function beginTransaction() : IDatabase {
        $this->pdoObject->beginTransaction();
        return $this;
    }

    /**
     * Commit the transaction
     *
     * @return IDatabase
     */
    public function commitTransaction() :IDatabase {
        $this->pdoObject->commit();
        return $this;
    }

    /**
     * Rolls back the transaction
     *
     * @return IDatabase
     */
    public function rollbackTransaction() : IDatabase {
        $this->pdoObject->rollBack();
        return $this;
    }

    /**
     * Fetch associative array
     *
     * @return array
     */
    public function fetchAssoc() : array {
        $result = $this->prepareStatement->fetchAll(PDO::FETCH_ASSOC);
        $this->freeResult();
        return $result;
    }

    /**
     * Fetch enumerated array
     *
     * @return array
     */
    public function fetchArray() : array {
        $result = $this->prepareStatement->fetchAll(PDO::FETCH_BOTH);
        $this->freeResult();
        return $result;
    }

    /**
     * Fetch Object instead of array
     *
     * @return array
     */
    public function fetchObject() : array {
        $result = $this->prepareStatement->fetchAll(PDO::FETCH_OBJ);
        $this->freeResult();
        return $result;
    }

    /**
     * Fetch the number of affected rows
     *
     * @return int number of rows
     */
    public function affectedRows() : int {
        return $this->prepareStatement->rowCount();
    }

    /**
     * Fetch the last inserted id
     *
     * @return int last row id of table
     */
    public function lastID() : int {
        return intval($this->pdoObject->lastInsertId());
    }

    /**
     * Fetch the ids of last entry
     *
     * @param int $size
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
     * Frees the database result
     *
     * @return void
     */
    public function freeResult() : void {
        $this->prepareStatement->closeCursor();
    }

    public function __destruct() {
        unset($this->pdoObject);
    }

    /**
     * @inheritDoc
     */
    public function sqlErrorNo(): int {
        return $this->pdoObject->errorCode();
    }
}