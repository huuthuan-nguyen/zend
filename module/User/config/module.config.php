<?php
namespace User;

use Application\Controller\IndexController;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use User\Controller\AuthController;
use User\Controller\UserController;
use User\Service\AuthAdapter;
use User\Service\AuthManager;
use User\Service\Factory\AuthenticationServiceFactory;
use User\Service\Factory\AuthManagerFactory;
use User\Service\UserManager;
use Zend\Authentication\AuthenticationService;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 8/22/2018
 * Time: 10:26 AM
 */
return [
    'service_manager' => [
        'factories' => [
            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthManager::class => AuthManagerFactory::class,
            AuthAdapter::class => AuthAdapterFactory::class,
            UserManager::class => UserManagerFactory::class
        ]
    ],
    // The 'access_filter' key is used by module User to restrict or permit
    // access to certain controller actions for unauthenticated visitors.
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed
            // under the 'access_filter' config key, and access is denied to any not listed
            // action for users not logged in. In permissive mode, if an action is not listed
            // under the 'access_filter' key, access to it is permitted to anyone (even for
            // users not logged in. Restrictive mode is more secure and recommended.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            UserController::class => [
                // Allow anyone to visit "resetPassword", "message" and "setPassword" actions
                ['actions' => ['resetPassword', 'message', 'setPassword'], 'allow' => '*'],
                // Allow authenticated users to visit "index", "add", "edit", "view", "changePassword" action
                ['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '@']
            ],
        ]
    ],
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'login'
                    ]
                ]
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'logout'
                    ]
                ]
            ],
            'reset-password' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/reset-password',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'resetPassword'
                    ]
                ]
            ],
            'users' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/users[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            AuthController::class => AuthControllerFactory::class,
            UserController::class => UserControllerFactory::class
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ]
    ],
    'view_helpers' => [
        'factories' => [
            CurrentUser::class => CurrentUserFactory::class
        ],
        'alias' => [
            'currentUser' => CurrentUser::class
        ]
    ],
    'controller_plugins' => [
        'factories' => [
            CurrentUserPlugin::class => CurrentUserPluginFactory::class
        ],
        'alias' => [
            'currentUser' => CurrentUserPlugin::class
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