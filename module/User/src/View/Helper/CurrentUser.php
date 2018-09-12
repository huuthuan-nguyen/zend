<?php
namespace User\View\Helper;

use Doctrine\ORM\EntityManager;
use User\Entity\User;
use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

/**
 * This view helper is used for retrieving the User entity of currently logged in user.
 * @package User\View\Helper
 */
class CurrentUser extends AbstractHelper {

    /**
     * Entity Manager.
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    private $authService;

    /**
     * Previously fetched User entity.
     * @var null
     */
    private $user = null;

    /**
     * CurrentUser constructor.
     * @param $entityManager
     * @param $authService
     */
    public function __construct($entityManager, $authService) {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    /**
     * Return the current user or null if not logged in.
     * @param bool $userCachedUser
     * @return null
     * @throws \Exception
     */
    public function __invoke($userCachedUser = true)
    {
        // CHeck if User is already fetched previously.
        if ($userCachedUser && $this->user!==null)
            return $this->user;

        // Check if user is logged in.
        if ($this->authService->hasIdentity()) {

            // Fetch user entity from database.
            $this->user = $this->entityManager->getRepository(User::class)->findOneBy([
                'user' => $this->authService->getIdentity()
            ]);

            // Oops.. the identity presents in session, but there is no such user in database.
            // We throw an exception, because this is a possible security problem.
            if ($this->user == null)
                throw new \Exception('Not found user with such ID');

            // Return the User entity we found.
            return $this->user;
        }

        return null;
    }
}