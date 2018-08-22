<?php
namespace Customer\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 4/24/2018
 * Time: 3:29 PM
 */

class AccessPlugin extends AbstractPlugin {
    public function checkAccess($actionName) {
        return true;
    }
}