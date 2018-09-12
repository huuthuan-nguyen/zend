<?php
namespace User\Controller;

use Doctrine\ORM\EntityManager;
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
class UserController extends AbstractActionController {

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
    public function __construct($entityManager, $userManager) {
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


    public function addAction() {
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
    public function viewAction() {
        $id = (int) $this->params()->fromRoute('id', -1);

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
    public function editAction() {

    }
}