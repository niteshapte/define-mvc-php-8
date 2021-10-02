<?php
declare(strict_types = 1);
namespace Application\Repository;

use Define\Exceptions\FrameworkException;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

class IndexRepository extends ApplicationRepository {

    /**
     * @throws FrameworkException
     */
    public function __construct() {
        $this->conn = $this->getConnection();
    }

    public function getValues() : array {
        $sql = "SELECT FROM dummytable WHERE abc = ? AND xyz = ?";
        $param = array("a", "b"); // values must in sequence i.e. value for abc will come first and then value for xyz
        return $this->conn->executeSql($sql, $param)->fetchAssoc();
    }
}