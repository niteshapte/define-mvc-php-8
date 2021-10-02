<?php
declare(strict_types = 1);
namespace Application\Service;

use Application\Repository\IndexRepository;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

class IndexService extends ApplicationService {

    private IndexRepository $indexRepository;

    public function __construct() {
        parent::__construct();
        $this->indexRepository = new IndexRepository();
    }

    public function testMessage(): string {
        return "This is message from Service";
    }

    public function getValuesFromRepository() : array {
        return $this->indexRepository->getValues();
    }
}