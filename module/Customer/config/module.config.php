<?php
/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/2/2018
 * Time: 11:50 AM
 */

use Customer\Controller\Plugin\AccessPlugin;
use Customer\Route\StaticRoute;
use Customer\View\Helper\Hello;
use Zend\Config\Config;
use Zend\Mvc\Controller\LazyControllerAbstractFactory;
use Customer\Factory\CustomerControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Regex;
use Zend\Router\Http\Segment;
use Customer\Controller\CustomerController;
use Zend\Router\Http\TreeRouteStack;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Storage\SessionArrayStorage;

return [
    'router' => [
        'router_class' => TreeRouteStack::class,
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
            ],
            'regex' => [
                'type' => Regex::class,
                'options' => [
                    'regex' => '/doc(?<page>\/[a-zA-Z0-9_\-]+)\.html',
                    'defaults' => [
                        'controller' => CustomerController::class,
                        'action' => 'doc'
                    ],
                    'spec' => '/doc/%page%.html'
                ]
            ],
            'static' => [
                'type' => StaticRoute::class,
                'options' => [
                    'dir_name' => __DIR__ . '/../view',
                    'template_prefix' => 'customer/customer/static',
                    'filename_pattern' => '/[a-z0-9_\-]+/',
                    'defaults' => [
                        'controller' => CustomerController::class,
                        'action' => 'static',
                    ]
                ]
            ],
            'placeholder' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/placeholder',
                    'defaults' => [
                        'controller' => CustomerController::class,
                        'action' => 'placeholder'
                    ]
                ]
            ],
            'session' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/session',
                    'defaults' => [
                        'controller' => CustomerController::class,
                        'action' => 'session'
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            //CustomerController::class => LazyControllerAbstractFactory::class
            CustomerController::class => CustomerControllerFactory::class
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
    ],
    'view_helpers' => [
        'invokables' => [
            Hello::class
        ],
        'aliases' => [
            'hello' => Hello::class
        ]
    ],
    'session_config' => [
        'cache_expire' => 3600,//1hours
        'gc_maxlifetime' => 2 * 3600, //1hours
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
];