<?php

namespace Customer\Controller;

use Zend\Http\Headers;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/9/2018
 * Time: 3:49 PM
 */
class CustomerController extends AbstractActionController
{
    public function indexAction() {

        //return new ViewModel();
        /*var_dump($this->access()->checkAccess('index'));die;
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type: application/json');
        return $this->getResponse()->setContent(json_encode(['test' => 'fuck']));*/
        $viewModel = new ViewModel();
        $viewModel->setTemplate('index');
        return new ViewModel();
    }

    public function jsonAction() {
        return new JsonModel([
            'test' => 'fuck'
        ]);
    }

    public function docAction() {
        $page = $this->params()->fromRoute('page', 'documentation.phtml');
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type: application/json');
        return $this->getResponse()->setContent(json_encode(['name' => $page]));
    }
}