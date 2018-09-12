<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\AuthManager;
use Zend\Authentication\AuthenticationService;
use Zend\Config\Config;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

class AuthManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate dependencies.
        $sessionManager = $container->get(SessionManager::class);
        $authenticationService = $container->get(AuthenticationService::class);

        // Get contents of 'access_filter' config key (the AuthManager service
        // will use this data to determine whether to allow currently logged in user
        // to execute the controller action or not.
        $config = $container->get(Config::class);
        if (isset($config['access_filter']))
            $config = $config['access_filter'];
        else
            $config = [];

        // Instantiate the AuthManager service and inject dependencies to its constructor.
        return new AuthManager($sessionManager, $authenticationService, $config);
    }
}