<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use User\View\Helper\CurrentUser;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

class CurrentUserFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService = $container->get(AuthenticationService::class);

        return new CurrentUser($entityManager, $authService);
    }
}