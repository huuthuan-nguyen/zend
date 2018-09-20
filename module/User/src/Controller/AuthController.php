<?php
namespace User\Controller;

use Doctrine\ORM\EntityManager;
use User\Form\LoginForm;
use User\Service\AuthManager;
use User\Service\UserManager;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for letting the user to log in and log out.
 *
 * Class AuthController
 * @package User\Controller
 */
class AuthController extends AbstractActionController {

    /**
     * EntityManager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Auth Manager.
     * @var AuthManager
     */
    private $authManager;

    /**
     * User manager.
     * @var UserManager
     */
    private $userManager;

    /**
     * AuthController constructor.
     * @param $entityManager
     * @param $authManager
     * @param $userManager
     */
    public function __construct(
        $entityManager,
        $authManager,
        $userManager
    ) {
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
    }

    /**
     * @return ViewModel
     * @throws \Exception
     */
    public function loginAction() {
        // Retrieve the redirect URL (if passed). We will redirect the user to this
        // URL after successful login.
        $redirectUrl = (string) $this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048)
            throw new \Exception("Too long redirectUrl argument passed.");

        // Check if we do not have users in database at all. If so, create
        // the 'Admin' user.
        $this->userManager->createAdminUserIfNotExists();

        // Create login form
        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        // Store login status
        $isLoginError = false;

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Perform login attempt.
                $result = $this->authManager->login($data['email'], $data['password'], $data['remember_me']);

                // Check result
                if ($result->getCode() == Result::SUCCESS) {

                    // Get redirect URL.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {
                        // The below check is to prevent possible redirect attack
                        // (if someone tries to redirect user to another domain).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }

                    // If redirect URL is provided, redirect the user to that URL;
                    // otherwise redirect to Home page.
                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    /**
     * The "logout" action performs logout operation.
     *
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    public function logoutAction() {
        $this->authManager->logout();

        return $this->redirect()->toRoute('login');
    }

    /**
     * Displays the "Not Authorized" page.
     * @return ViewModel
     */
    public function notAuthorizedAction() {
        $this->getResponse()->setStatusCode(403);
        return new ViewModel();
    }
}