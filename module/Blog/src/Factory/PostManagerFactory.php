<?php
namespace Blog\Factory;
use Blog\Service\PostManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 4/9/2018
 * Time: 5:06 PM
 */

class PostManagerFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Instantiate the service and inject dependencies
        return new PostManager($entityManager);
    }
}