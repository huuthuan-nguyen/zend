<?php
namespace User\Service;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 8/22/2018
 * Time: 10:31 AM
 */

class AuthManager {

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * AuthManager constructor.
     * @param SessionManager $sessionManager
     * @param AuthenticationService $authService
     */
    public function __construct(SessionManager $sessionManager, AuthenticationService $authService)
    {
        $this->sessionManager = $sessionManager;
        $this->authService = $authService;
    }

    /**
     * Performs a login attempt. If $rememberMe argument is true, if forces the session
     * to last for one month (otherwise the session expires on one hour).
     *
     * @param $email
     * @param $password
     * @param $rememberMe
     * @return Result
     * @throws \Exception
     */
    public function login($email, $password, $rememberMe) {
        if ($this->authService->getIdentity() != null)
            throw new \Exception('Already logged in');

        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);
        $result = $this->authService->authenticate();

        // If user wants to "remember him", we will make session to expire in
        // one month. By default session expires in 1 hour (as specific in our
        // config/global.php file).
        if ($result->getCode() == Result::SUCCESS && $rememberMe)
            // Session cookie will expire in 1 month (30 days).
            $this->sessionManager->rememberMe(60*60*24*30);

        return $result;
    }

    /**
     * Performs user logout.
     * @throws \Exception
     */
    public function logout() {
        // Allow to log out only when user is logged in.
        if ($this->authService->getIdentity() == null)
            throw new \Exception('The user is not logged in');

        // Remove identity form session.
        $this->authService->clearIdentity();
    }
}