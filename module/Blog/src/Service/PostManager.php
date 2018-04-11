<?php

namespace Blog\Service;

use Blog\Entity\Comment;
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

    public function addNewPost($data)
    {
        $post = new Post();

        $post->setTitle($data['title']);
        $post->setText($data['text']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    // This method allows to update data of a single post.
    public function updatePost($post, $data)
    {
        $post->setTitle($data['title']);
        $post->setText($data['text']);

        $this->entityManager->flush();
    }

    // Removes post and all associated comments.
    public function removePost($post)
    {

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

    // Return count of comments for given post as properly formatted string.
    public function getCommentCountStr($post)
    {

        $commentCount = count($post->getComments());
        if ($commentCount == 0)
            return 'No comments';
        elseif ($commentCount == 1)
            return '1 comment';
        else
            return $commentCount . ' comments';
    }

    // This method adds a new comment to post.
    public function addCommentToPost($post, $data)
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($data['author']);
        $comment->setContent($data['comment']);
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($comment);

        // Apply changes:
        $this->entityManager->flush();
    }

    // Converts tags of the given post to comma separated list (string).
    public function convertTagsToString($post)
    {
        $tags = $post->getTags();
        $tagCount = count($tags);
        $tagsStr = '';
        $i = 0;
        foreach ($tags as $tag) {
            $i++;
            $tagsStr .= $tag->getName();
            if ($i < $tagCount)
                $tagsStr .= ', ';
        }

        return $tagsStr;
    }
}