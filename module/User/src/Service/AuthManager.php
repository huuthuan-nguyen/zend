<?php
namespace User\Service;
use Zend\Authentication\AuthenticationService;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 8/22/2018
 * Time: 10:31 AM
 */

class AuthManager {

    /**
     * @var AuthenticationService
     */
    private $authService;

    public function login($email, $password, $rememberMe) {
        if ($this->authService->getIdentity() != null)
            throw new \Exception('Already logged in');

        $authAdapter = $this->authService->getAdapter();
    }
}