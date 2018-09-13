<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\UserController;
use User\Service\UserManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 * @package User\Controller\Factory
 */
class UserControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);

        // Instantiate the controller and inject dependencies
        return new UserController($entityManager, $userManager);
    }
}