<?php
namespace Customer;
/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/2/2018
 * Time: 11:46 AM
 */

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface {

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}