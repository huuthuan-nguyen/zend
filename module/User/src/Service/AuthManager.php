<?php
namespace User\Service;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;

/**
 * Class AuthManager
 * @package User\Service
 */
class AuthManager {

    // Constants returned by the access filter.
    const ACCESS_GRANTED = 1; // Access to the page is granted.
    const AUTH_REQUIRED = 2; // Authentication is required to see the page.
    const ACCESS_DENIED = 3; // Access to the page is denied.

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * Contents of the 'access_filter' config key.
     * @var array
     */
    protected $config;

    /**
     * AuthManager constructor.
     * @param SessionManager $sessionManager
     * @param AuthenticationService $authService
     * @param array $config
     */
    public function __construct(SessionManager $sessionManager, AuthenticationService $authService, $config)
    {
        $this->sessionManager = $sessionManager;
        $this->authService = $authService;
        $this->config = $config;
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

    /**
     * @param $controllerName
     * @param $actionName
     * @return bool
     * @throws \Exception
     */
    public function filterAccess($controllerName, $actionName) {
        $mode = isset($this->config['options']['mode']) ? $this->config['options']['mode'] : 'restrictive';
        if ($mode != 'restrictive' && $mode != 'permissive')
            throw new \Exception('Invalid filter access mode (expected either restrictive or permissive mode)');

        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList == '*') {
                    if ($allow == '*')
                        return true; // Anyone is allowed to see the page.
                    elseif ($allow == '@' && $this->authService->hasIdentity()) {
                        return true; // Only authenticated user is allowed to see the page.
                    } else {
                        return false; // Access denied.
                    }
                }
            }
        }

        // In restrictive mode, we forbid access for authenticated users to any
        // action not listed under 'access_filter' key (for security reasons).
        if ($mode == 'restrictive' && !$this->authService->hasIdentity())
            return false;

        // Permit access to this page.
        return true;
    }
}