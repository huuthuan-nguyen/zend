<?php
namespace User;
use User\Service\Factory\AuthenticationServiceFactory;
use Zend\Authentication\AuthenticationService;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 8/22/2018
 * Time: 10:26 AM
 */
return [
    'service_manager' => [
        'factories' => [
            AuthenticationService::class => AuthenticationServiceFactory::class
        ]
    ]
];