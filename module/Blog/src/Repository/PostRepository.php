<?php
/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 4/12/2018
 * Time: 10:56 AM
 */
namespace Blog\Repository;

use Doctrine\ORM\EntityRepository;
use Blog\Entity\Post;

// This is the custom repository class for Post entity.
class PostRepository extends EntityRepository {

    // Finds all published posts having any tag.
    public function findPostHavingAnyTag() {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC');

        $posts = $queryBuilder->getQuery()->getResult();

        return $posts;
    }

    // Finds all published posts having the given tag.
    public function findPostsByTag($tagName) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from(Post::class, 'p')
            ->join('p.tags', 't')
            ->orderBy('p.dateCreated', 'DESC');

        $posts = $queryBuilder->getQuery()->getResult();

        return $posts;
    }
}