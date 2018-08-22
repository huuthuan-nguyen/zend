<?php
/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 4/9/2018
 * Time: 3:54 PM
 */

namespace Blog\Factory;


use Blog\Controller\IndexController;
use Blog\Form\PostForm;
use Blog\Model\PostCommandInterface;
use Blog\Service\PostManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements  FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);
        $formManager = $container->get('FormElementManager');
        return new IndexController($entityManager,
            $postManager,
            $container->get(PostCommandInterface::class),
            $formManager->get(PostForm::class)
        );
    }
}