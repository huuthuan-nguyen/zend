<?php
namespace User\Service\Factory;
use Interop\Container\ContainerInterface;
use User\Service\AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Session\Storage\SessionStorage;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 8/23/2018
 * Time: 11:03 AM
 */

/**
 * The factory responsible for creating of authentication service.
 * Class AuthenticationServiceFactory
 * @package User\Service\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface {

    /**
     * This method creates the Zend\Authentication\AuthenticationService service
     * and return its instance.
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new SessionStorage('Zend_Auth', 'session', $sessionManager);
        $authAdapter = $container->get(AuthAdapter::class);

        // Create the service and inject dependencies into its constructor.
        return new AuthenticationService($authStorage, $authAdapter);
    }
}