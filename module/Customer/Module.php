<?php
namespace Customer;
/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 4/2/2018
 * Time: 11:46 AM
 */

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

class Module implements ConfigProviderInterface {

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * This method is called once the MVC bootstrapping is complete.
     *
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event) {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // The following line instantiates the SessionManager and automatically.
        // makes the SessionManager the 'default' one.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }
}