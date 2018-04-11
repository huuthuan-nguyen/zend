<?php

namespace Blog\Controller;

use Blog\Form\CommentForm;
use Blog\Entity\Post;
use Blog\Form\PostForm;
use Blog\Model\PostCommandInterface;
use Blog\Service\PostManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/9/2018
 * Time: 3:49 PM
 */
class IndexController extends AbstractActionController
{

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
                                PostCommandInterface $command, PostForm $form)
    {
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

    public function addAction()
    {

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

    public function editAction()
    {
        // get post Id
        $postId = $this->params()->fromRoute('id', -1);

        // find existing post in the database
        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);


        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $this->form->setData($data);
            if ($this->form->isValid()) {

                // get validated form data.
                $data = $this->form->getData();

                // use post manager service to add new post to database.
                $this->postManager->updatePost($post, $data['post']);

                // redirect the user to "admin" page.
                return $this->redirect()->toRoute('index', ['action' => 'index']);
            }

        } else {
            $data = [
                'post' => [
                    'title' => $post->getTitle(),
                    'text' => $post->getText()
                ]
            ];

            $this->form->setData($data);
        }

        // render the view template
        return new ViewModel([
            'form' => $this->form,
            'post' => $post
        ]);
    }

    // This "delete" action displays the Delete Post page.
    public function deleteAction() {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->postManager->removePost($post);

        // Redirect the user to "index" page
        return $this->redirect()->toRoute('index', ['action' => 'index']);
    }

    /**
     * This action displays the "View Post" page allowing to see the post title
     * and content. The page also contains a form allowing
     * to add a comment to post.
     */
    public function viewAction() {
        $postId = $this->params()->fromRoute('id', -1);

        $post = $this->entityManager->getRepository(Post::class)
            ->findOneById($postId);

        if ($post == null) {
            $this->getResponse()->getStatusCode(404);
            return;
        }

        $commentCount = $this->postManager->getCommentCountStr($post);

        // Create the form.
        $form = new CommentForm();

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {

            // Get POST data.
            $data = $this->params()->fromPost();

            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Use post manager service to add new comment to post.
                $this->postManager->addCommentToPost($post, $data);

                // Redirect the user again to "view" page.
                return $this->redirect()->toRoute('view', ['action' => 'view', 'id' => $postId]);
            }
        }

        // Render the view template
        return new ViewModel([
            'post' => $post,
            'commentCount' => $commentCount,
            'form' => $form,
            'postManager' => $this->postManager
        ]);
    }
}