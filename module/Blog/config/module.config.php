<?php
namespace Blog;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'blog' => [
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
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\ListController::class => Factory\ListControllerFactory::class,
            Controller\WriteController::class => Factory\WriteControllerFactory::class
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
            Model\ZendDbSqlRepository::class => Factory\ZendDbSqlRepositoryFactory::class
        ],
        'invokables' => [
            Model\PostCommand::class
        ]
    ]
];