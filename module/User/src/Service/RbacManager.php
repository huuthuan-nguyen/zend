<?php
namespace User\Service;

use Doctrine\ORM\EntityManager;
use User\Entity\Role;
use Zend\Authentication\AuthenticationService;
use Zend\Cache\Storage\StorageInterface;
use Zend\Permissions\Rbac\Rbac;
use User\Entity\User;

/**
 * This service is used for invoking user-defined RBAC dynamic assertion.
 * @package User\Service
 */
class RbacManager
{
    /**
     * Entity Manager.
     * @var EntityManager
     */
    private $entityManager;

    /**
     * RBAC Service.
     * @var Rbac
     */
    private $rbac;

    /**
     * Auth Service.
     * @var AuthenticationService
     */
    private $authService;

    /**
     * Filesystem cache.
     * @var StorageInterface
     */
    private $cache;

    /**
     * Assertions manager.
     * @var array
     */
    private $assertionManagers = [];

    /**
     * RbacAssertionManager constructor.
     * @param $entityManager
     * @param $authService
     * @param $cache
     * @param $assertionManagers
     */
    public function __construct($entityManager, $authService, $cache, $assertionManagers)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
        $this->cache = $cache;
        $this->assertionManagers = $assertionManagers;
    }

    /**
     * Initializes the RBAC container.
     * @param bool $forceCreate
     */
    public function init($forceCreate = false) {
        if ($this->rbac != null && !$forceCreate) {
            // already initialized, do nothing.
            return ;
        }

        // if user wants us to re-init RBAC container, clear cache now.
        if ($forceCreate)
            $this->cache->removeItem('rbac_container');
        // try to load Rbac container from cache.
        $this->rbac = $this->cache->getItem('rbac_container', $result);
        if (!$result) {
            // Create Rbac container.
            $rbac = new Rbac();
            $this->rbac = $rbac;

            // Construct role hierarchy by loading roles and permissions from database.
            $rbac->setCreateMissingRoles(true);

            $roles = $this->entityManager->getRepository(Role::class)
                ->findBy([], ['id' => 'ASC']);
            foreach ($roles as $role) {
                $roleName = $role->getName();

                $parentRoleNames = [];

                foreach ($role->getParentRoles() as $parentRole) {
                    $parentRoleNames[] = $parentRole->getName();
                }

                $rbac->addRole($roleName, $parentRoleNames);

                foreach ($role->getPermissions() as $permission) {
                    $rbac->getRole($roleName)->addPermission($permission->getName);
                }
            }

            // Save Rbac container to cache.
            $this->cache->setItem('rbac_container', $rbac);
        }
    }

    /**
     * Checks whether the given user has permission
     * @param $user
     * @param $permission
     * @param null $params
     * @return bool
     * @throws \Exception
     */
    public function isGranted($user, $permission, $params = null) {
        if ($this->rbac == null)
            $this->init();

        if ($user == null) {

            $identity = $this->authService->getIdentity();
            if ($identity == null)
                return false;

            $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($identity);

            if ($user == null)
                // Oops..the identity presents in session, but there is no such user in database.
                // We throw an exception, because this is a possible security problem.
                throw new \Exception('There is no user with such identity');
        }

        $roles = $user->getRoles();

        foreach ($roles as $role) {
            if ($this->rbac->isGranted($role->getName(), $permission)) {

                if ($params == null)
                    return true;

                foreach ($this->assertionManagers as $assertionManager) {
                    if ($assertionManager->assert($this->rbac, $permission, $params))
                        return true;
                }
            }

            $parentRoles = $role->getParentRoles();
            foreach ($parentRoles as $parentRole) {
                if ($this->rbac->isGranted($parentRole->getName(), $permission))
                    return true;
            }
        }
        return false;
    }
}