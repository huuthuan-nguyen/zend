<?php
namespace User\Controller\Plugin;

use Doctrine\ORM\EntityManager;
use User\Entity\User;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class CurrentUserPlugin extends AbstractPlugin {

    /**
     * Entity Manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    private $authService;

    /**
     * Login user.
     * @var User
     */
    private $user = null;

    /**
     * CurrentUserPlugin constructor.
     * @param $entityManager
     * @param $authService
     */
    public function __construct($entityManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    public function __invoke($useCachedUser = true)
    {
        // If current user is already fetched, return it
        if ($useCachedUser && $this->user !== null)
            return $this->user;

        // check if user is logged in.
        if ($this->authService->hasIdentity()) {

            // fetch user entity from database.
            $this->user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($this->authService->getIdentity());

            if ($this->user == null) {
                // Oops.. the identity presents in session, but there is no such user in database.
                // We throw an exception, because this is a possible security problem.
                throw new \Exception('Not found user with such email');
            }

            // Return found user.
            return $this->user;
        }

        return null;
    }
}