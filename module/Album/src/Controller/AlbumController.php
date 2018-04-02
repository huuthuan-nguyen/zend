<?php
/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/2/2018
 * Time: 1:58 PM
 */

namespace Album\Controller;

use Album\Model\AlbumTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private $table;

    public function __construct(AlbumTable $table) {
        $this->table = $table;
    }

    public function indexAction() {

        return new ViewModel([
            'albums' => $this->table->fetchAll()
        ]);
    }

    public function addAction() {

    }

    public function editAction() {

    }

    public function deleteAction() {

    }
}