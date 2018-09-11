<?php
namespace User\Service\Factory;
use Interop\Container\ContainerInterface;
use User\Service\AuthManager;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 29/08/2018
 * Time: 10:46 PM
 */

class AuthManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $authenticationService = $container->get(AuthenticationService::class);
        return new AuthManager($sessionManager, $authenticationService);
    }
}