<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController {
    public function indexAction()
    {
        return "Index Action";
    }
}