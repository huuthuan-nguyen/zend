<?php
namespace Album;
/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/2/2018
 * Time: 11:50 AM
 */
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Album\Controller\AlbumController;

return [
    /*'controllers' => [
        'factories' => [
            Controller\AlbumController::class => InvokableFactory::class
        ]
    ],*/
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view'
        ]
    ],
    'router' => [
        'routes' => [
            'album' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller' => AlbumController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ]
];