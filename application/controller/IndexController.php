<?php
declare(strict_types = 1);
namespace Application\Controller;

use Application\Service\IndexService;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

class IndexController extends ApplicationController {

    private IndexService $service;

    public function __construct() {
        parent::__construct();
        $this->service = new IndexService();
    }

    public function defaultAction(string $one = "", string $two = "") {
        echo "From Controller";
        $this->logger->info("Controller page");
        $this->view->addObject("mesg", "<br>Home Page");
        $this->view->render('index');
    }

    public function testFromServiceAction() {
        $this->view->addObject("mesg", $this->service->testMessage());
        $this->view->render('index');
    }
}