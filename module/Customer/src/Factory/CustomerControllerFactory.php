<?php
namespace Customer\Factory;

use Customer\Controller\CustomerController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/24/2018
 * Time: 2:23 PM
 */

class CustomerControllerFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        echo '<pre>';var_dump($sessionManager);die;
        return new CustomerController($sessionManager);
    }
}