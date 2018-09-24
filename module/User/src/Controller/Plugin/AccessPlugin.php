<?php
namespace User\Controller\Plugin;

use User\Service\RbacManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * This controller plugin is used for role-based access control (RBAC)
 * @package User\Controller\Plugin
 */
class AccessPlugin extends AbstractPlugin {

    /**
     * @var RbacManager
     */
    private $rbacManager;

    public function __construct($rbacManager)
    {
        $this->rbacManager = $rbacManager;
    }

    /**
     * Checks whether the currently logged in user has the given permission.
     * @param $permission
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($permission, $params = [])
    {
        return $this->rbacManager->isGranted(null, $permission, $params);
    }
}