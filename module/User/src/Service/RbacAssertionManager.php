<?php
namespace User\Service;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Rbac\Rbac;
use User\Entity\User;

/**
 * This service is used for invoking user-defined RBAC dynamic assertion.
 * @package User\Service
 */
class RbacAssertionManager
{
    /**
     * Entity Manager.
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Auth Service.
     * @var AuthenticationService
     */
    private $authService;

    /**
     * RbacAssertionManager constructor.
     * @param $entityManager
     * @param $authService
     */
    public function __construct($entityManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    /**
     * This method is used for dynamic assertions.
     * @param Rbac $rbac
     * @param $permission
     * @param $params
     * @return bool
     */
    public function assert(Rbac $rbac, $permission, $params) {
        $currentUser = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($this->authService->getIdentity());

        if ($permission == 'profile.own.view' && $params['user']->getId() == $currentUser->getId())
            return true;

        return false;
    }
}