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
        return new CustomerController($container->get(SessionManager::class));
    }
}