<?php
namespace User\Service\Factory;

use Doctrine\Common\Cache\FilesystemCache;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Config\Config;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Service\RbacManager;

/**
 * This is the factory class for RbacManager service. The purpose of the factory
 * is to instantiate the service and pass id dependencies (inject dependencies)
 * @package User\Service\Factory
 */
class RbacManagerFactory implements FactoryInterface {
    /**
     * This method creates the RbacManager service and returns its instance.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|void
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctine.entitymanager.orm_default');
        $authService = $container->get(AuthenticationService::class);
        $cache = $container->get(FilesystemCache::class);

        $assertionManagers = [];
        $config = $container->get(Config::class);
        if (isset($config['rbac_manager']['assertions'])) {
            foreach ($config['rbac_manager']['asserttions'] as $serviceName) {
                $assertionManagers[$serviceName] = $container->get($serviceName);
            }
        }

        return new RbacManager($entityManager, $authService, $cache, $assertionManagers);
    }
}