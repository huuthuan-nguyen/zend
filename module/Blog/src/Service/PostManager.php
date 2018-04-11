<?php

namespace Blog\Service;
use Blog\Entity\Post;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/9/2018
 * Time: 5:03 PM
 */

class PostManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addNewPost($data) {
        $post = new Post();

        $post->setTitle($data['title']);
        $post->setText($data['text']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    // This method allows to update data of a single post.
    public function updatePost($post, $data) {
        $post->setTitle($data['title']);
        $post->setText($data['text']);

        $this->entityManager->flush();
    }

    // Removes post and all associated comments.
    public function removePost($post) {

        // Remove associated comments
        $comments = $post->getComments();
        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }

        // Remove tag associations (if any)
        $tags = $post->getTags();
        foreach ($tags as $tag) {
            $post->removeTagAssociation($tag);
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}