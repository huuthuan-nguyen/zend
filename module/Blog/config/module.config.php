<?php
namespace Blog;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Blog\Controller\IndexController;
use Blog\Factory\IndexControllerFactory;

return [
    'router' => [
        'routes' => [
            /*'blog' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/blog',
                    'defaults' => [
                        'controller' => Controller\ListController::class,
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'detail' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/:id',
                            'defaults' => [
                                'action' => 'detail'
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*'
                            ]
                        ]
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'controller' => Controller\WriteController::class,
                                'action' => 'add'
                            ]
                        ]
                    ]
                ]
            ],*/
            'index' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/blog/index',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            'add' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/blog/add',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'add'
                    ]
                ]
            ],
            'edit' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/blog/edit[/:id]',
                    'constraints' => [
                        'id' => '[1-9]+'
                    ],
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'edit'
                    ],
                ]
            ],
            'delete' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/blog/delete[/:id]',
                    'constraints' => [
                        'id' => '[1-9]+'
                    ],
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'delete'
                    ],
                ]
            ],
            'view' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/blog/view[/:id]',
                    'constraints' => [
                        'id' => '[1-9]+'
                    ],
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'view'
                    ],
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\ListController::class => Factory\ListControllerFactory::class,
            Controller\WriteController::class => Factory\WriteControllerFactory::class,
            IndexController::class => IndexControllerFactory::class
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view'
        ]
    ],
    'service_manager' => [
        'aliases' => [
            //Model\PostRepositoryInterface::class => Model\PostRepository::class
            Model\PostRepositoryInterface::class => Model\ZendDbSqlRepository::class,
            Model\PostCommandInterface::class => Model\PostCommand::class
        ],
        'factories' => [
            Model\PostRepository::class => InvokableFactory::class,
            Model\ZendDbSqlRepository::class => Factory\ZendDbSqlRepositoryFactory::class,
            Service\PostManager::class => Factory\PostManagerFactory::class
        ],
        'invokables' => [
            Model\PostCommand::class,
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];