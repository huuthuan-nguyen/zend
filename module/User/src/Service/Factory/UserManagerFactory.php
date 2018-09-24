<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\UserManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory class for UserManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies)
 * @package User\Service\Factory
 */
class UserManagerFactory implements FactoryInterface {
    // This method creates the UserManager service and returns its instance.
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $viewRenderer = $container->get('ViewRenderer');
        $config = $container->get('config');

        return new UserManager($entityManager, $viewRenderer, $config);
    }
}