<?php

namespace Customer;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/2/2018
 * Time: 11:50 AM
 */

use Customer\Controller\Plugin\AccessPlugin;
use Customer\Factory\CustomerControllerFactory;
use Zend\Config\Config;
use Zend\Mvc\Controller\LazyControllerAbstractFactory;
use Zend\Router\Http\Segment;
use Customer\Controller\CustomerController;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'customer' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/customer[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller' => CustomerController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            CustomerController::class => LazyControllerAbstractFactory::class
        ]
    ],
    'service_manager' => [
        'services' => [
            'test' => new Config(['a' => 'A', 'b' => 'B'])
        ]
    ],
    'controller_plugins' => [
        'factories' => [
            AccessPlugin::class => InvokableFactory::class,
        ],
        'aliases' => [
            'access' => AccessPlugin::class
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'index' => __DIR__ . '/../view/customer/customer/index.phtml',
        ],
        'template_path_stack' => [
            'customer' => __DIR__ . '/../view'
        ],
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ]
];