<?php
namespace User\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\Plugin\CurrentUserPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

class CurrentUserPluginFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService = $container->get(AuthenticationService::class);

        return new CurrentUserPlugin($entityManager, $authService);
    }
}