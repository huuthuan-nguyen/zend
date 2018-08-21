<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
    'db' => [
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=zend;host=localhost',
        'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"),
        'username' => 'zend',
        'password' => 'zend'
    ],
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ],
    ],
    'translator' => [
        'local' => 'en_US',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => getcwd() . '/data/language',
                'pattern' => '%s.mo'
            ]
        ]
    ],
    // session config
    'session_config' => [
        // store cookie for 1 hour
        'cookie_lifetime' => 60 * 60 * 1,
        // store session for 30 days
        'gc_maxlifetime' => 60 * 60 * 24 * 30
    ],
    // session manager
    'session_manager' => [
        // Session validators (used for security)
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class
        ],
    ],
    // session storage configuration
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'session_containers' => [
        'FuckContainerNamespace'
    ]
];
