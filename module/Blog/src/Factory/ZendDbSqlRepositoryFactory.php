<?php
namespace Blog\Factory;

use Blog\Model\Post;
use Blog\Model\ZendDbSqlRepository;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection;
use Zend\ServiceManager\Factory\FactoryInterface;

class ZendDbSqlRepositoryFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ZendDbSqlRepository($container->get(AdapterInterface::class),
            new Reflection(),
            new Post('', ''));
    }
}