<?php
namespace User;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use User\Controller\AuthController;
use User\Controller\Factory\AuthControllerFactory;
use User\Controller\Factory\UserControllerFactory;
use User\Controller\Plugin\CurrentUserPlugin;
use User\Controller\Plugin\Factory\CurrentUserPluginFactory;
use User\Controller\UserController;
use User\Service\AuthAdapter;
use User\Service\AuthManager;
use User\Service\Factory\AuthAdapterFactory;
use User\Service\Factory\AuthenticationServiceFactory;
use User\Service\Factory\AuthManagerFactory;
use User\Service\Factory\UserManagerFactory;
use User\Service\RbacManager;
use User\Service\UserManager;
use User\View\Helper\CurrentUser;
use User\View\Helper\Factory\CurrentUserFactory;
use Zend\Authentication\AuthenticationService;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

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
            'mode' => 'permissive'
        ],
        'controllers' => [
            UserController::class => [
                // Allow anyone to visit "resetPassword", "message" and "setPassword" actions
                ['actions' => ['resetPassword', 'message', 'setPassword'], 'allow' => '*'],
                // Allow authenticated users to visit "index", "add", "edit", "view", "changePassword" action
                ['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '@'],
                // Allow authenticated users to visit "test1", "test2", "test3" action
                // ['actions' => ['test1', 'test2', 'test3'] , 'allow' => '@']
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
            ],
            'not-authorized' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/not-authorized',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'notAuthorized'
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
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
        'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ]
    ],
    'view_helpers' => [
        'factories' => [
            CurrentUser::class => CurrentUserFactory::class,
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
    ],
    'cache' => [
        FilesystemCache::class => [
            'adapter' => [
                'name' => Filesystem::class,
                'options' => [
                    // store cache data in this directory.
                    'cache_dir' => './data/cache',
                    // store cached data for 1 hour.
                    'ttl' => 60*60*1
                ]
            ],
            'plugin' => [
                'name' => 'serializer',
                'options' => []
            ]
        ]
    ],
    'rbac_manager' => [
        'assertions' => [RbacManager::class]
    ]
];