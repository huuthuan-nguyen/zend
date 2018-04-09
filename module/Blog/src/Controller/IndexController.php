<?php
namespace Blog\Controller;
use Blog\Entity\Post;
use Blog\Form\PostForm;
use Blog\Model\PostCommandInterface;
use Blog\Service\PostManager;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/9/2018
 * Time: 3:49 PM
 */

class IndexController extends AbstractActionController {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Blog\Service\PostManager
     */
    private $postManager;

    private $command;
    private $form;

    public function __construct(EntityManager $entityManager, PostManager $postManager,
                                PostCommandInterface $command, PostForm $form) {
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
        $this->command = $command;
        $this->form = $form;
    }

    public function indexAction()
    {
        $posts = $this->entityManager->getRepository(Post::class)
            ->findAll();

        return new ViewModel([
            'posts' => $posts
        ]);
    }

    public function addAction() {

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->form->setData($data);

            if ($this->form->isValid()) {

                $data = $this->form->getData();

                $this->postManager->addNewPost($data['post']);

                return $this->redirect()->toRoute();
            }
        }


        return new ViewModel(['form' => $this->form]);
    }
}