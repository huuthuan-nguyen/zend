<?php

namespace User\Controller;

use Doctrine\ORM\EntityManager;
use User\Form\PasswordChangeForm;
use User\Form\PasswordResetForm;
use User\Form\UserForm;
use Zend\Paginator\Paginator;
use User\Entity\User;
use User\Service\UserManager;
use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password)
 * @package User\Controller
 */
class UserController extends AbstractActionController
{

    /**
     * Entity Manager.
     * @var EntityManager
     */
    private $entityManager;

    /**
     * User Manager.
     * @var UserManager
     */
    private $userManager;

    /**
     * UserController constructor.
     * @param $entityManager
     * @param $userManager
     */
    public function __construct($entityManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * list of users.
     * @return ViewModel
     */
    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);
        $query = $this->entityManager->getRepository(User::class)
            ->findAllUsers();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'users' => $paginator
        ]);
    }


    public function addAction()
    {
        // create user form
        $form = new UserForm('create', $this->entityManager);

        // check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // get filtered and validated data
                $data = $form->getData();

                // Add user.
                $user = $this->userManager->addUser($data);

                // Redirect to "view" page.
                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * The "view" action displays a page allowing to view user's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);

        if ($id < 1) {
            $this->getResponse()->setStattusCode(404);
            return $this->getResponse();
        }

        // Find a user with such ID.
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        return new ViewModel([
            'user' => $user
        ]);
    }

    /**
     * The "edit" action displays a page allowing to edit user.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 1);

        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        // Create user form
        $form = new UserForm('update', $this->entityManager, $user);

        // check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filters and validated data.
                $data = $form->getData();

                // Update the user.
                $this->userManager->updateUser($user, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('users',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        } else {
            $form->setData([
                'full_name' => $user->getFullName(),
                'email' => $user->getEmail(),
                'status' => $user->getStatus()
            ]);
        }

        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }

    /**
     * This action displays a page allowing to change user's password.
     */
    public function changePasswordAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);

        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        // Create "change password" form.
        $form = new PasswordChangeForm('change');

        // check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data.
            $data = $this->params()->fromPost();

            $form->setData($data);

            // validate form
            if ($form->isValid()) {

                // get filtered and validated data
                $data = $form->getData();

                // Try to change password.
                if (!$this->userManager->changePassword($user, $data))
                    $this->flashMessenger()->addErrorMessage(
                        'Sorry, the old password is incorrect. Could not set the new password.'
                    );
                else
                    $this->flashMessenger()->addSuccessMessage(
                        'Change the password successfully.'
                    );
            }

            // Redirect to "view" page.
            return $this->redirect()->toRoute('users',
                ['action' => 'view', 'id' => $user->getId()]);
        }
        return new ViewModel([
            'user' => $user,
            'form' => $form
        ]);
    }

    /**
     * This action display the "Reset Password" page.
     */
    public function resetPasswordAction()
    {
        // Create form
        $form = new PasswordResetForm();

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // look for the user with such email.
                $user = $this->entityManager->getRepository(User::class)
                    ->findByEmail($data['email']);

                if ($user != null && $user->getStatus() == User::STATUS_ACTIVE) {
                    // generate a new password for user and send an E-mail.
                    // notification about that.
                    $this->userManager->generatePasswordResetToken($user);

                    // redirect to "message" page.
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'send']);
                } else {
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'invalid-email']);
                }
            }
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * This action displays an informational message page.
     * For example "Your password has been resetted" and so on.
     */
    public function messageAction()
    {
        // Get message ID from route.
        $id = (string) $this->params()->fromRoute('id');

        // Validate input argument.
        if ($id != 'invalid-email' && $id != 'sent' && $id != 'set' && $id != 'failed')
            throw new \Exception('Invalid message ID specified');

        return new ViewModel([
            'id' => $id
        ]);
    }

    /**
     * This action displays the "Reset Password" page.
     */
    public function setPasswordAction() {
        $email = $this->params()->fromQuery('email', null);
        $token = $this->params()->fromQuery('token', null);

        // Validate token length
        if ($token != null && (!is_string($token) || strlen($token) != 32))
            throw new \Exception('Invalid token type or length');

        if ($token === null ||
            !$this->userManager->validatePasswordResetToken($email, $token))
                return $this->redirect()->toRoute('users',
                    ['action' => 'message', 'id' => 'failed']);

        // create form
        $form = new PasswordChangeForm('reset');

        // check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // fill in the form with POST data.
            $data = $this->params()->fromPost();

            $form->setData($data);

            // validate form
            if ($form->isValid()) {

                $data = $form->getData();

                // set new password for the user.
                if ($this->userManager->setNewPasswordByToken($email, $token, $data['new_password'])) {

                    // Redirect to "message" page
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'set']);
                } else {
                    // redirect to "message" page
                    return $this->redirect()->toRoute('users',
                        ['action' => 'message', 'id' => 'failed']);
                }
            }
        }
        return new ViewModel(['form' => $form]);
    }

    public function test1Action() {
        return new ViewModel();
    }

    public function test2Action() {
        return new ViewModel();
    }

    public function test3Action() {
        return new ViewModel();
    }
}