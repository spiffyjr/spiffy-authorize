<?php

namespace SpiffyAuthorize\Collector;

use RecursiveIteratorIterator;
use ReflectionClass;
use Serializable;
use SpiffyAuthorize\Service\RbacService;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\CollectorInterface;

class PermissionCollector implements
    CollectorInterface,
    Serializable
{
    const NAME     = 'spiffy_authorize_permission_collector';
    const PRIORITY = 100;

    /**
     * @var RbacService
     */
    protected $rbacService;

    /**
     * @var array
     */
    protected $permissions = array();

    /**
     * @param RbacService $rbacService
     */
    public function __construct(RbacService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Collector Name.
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Collector Priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return self::PRIORITY;
    }

    /**
     * Collects data.
     *
     * @param MvcEvent $mvcEvent
     * @return void
     */
    public function collect(MvcEvent $mvcEvent)
    {
        $service  = $this->rbacService;
        $provider = $this->rbacService->getIdentityProvider();

        if (!$provider) {
            return;
        }

        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac        = $service->getContainer();
        $roles       = $provider->getIdentityRoles();
        $permissions = array();

        // No getPermissions() available, have to use reflection.
        $reflClass    = new ReflectionClass('Zend\Permissions\Rbac\Role');
        $reflProperty = $reflClass->getProperty('permissions');
        $reflProperty->setAccessible(true);

        foreach ($roles as $role) {
            if ($rbac->hasRole($role)) {
                if (!isset($permissions[$role])) {
                    $permissions[$role] = array();
                }
                $permissions[$role] = array_merge($reflProperty->getValue($rbac->getRole($role)), $permissions[$role]);

                $it = new RecursiveIteratorIterator($rbac->getRole($role), RecursiveIteratorIterator::SELF_FIRST);
                foreach ($it as $leaf) {
                    $permissions[$role] = array_merge($reflProperty->getValue($leaf), $permissions[$role]);
                }
            }
        }

        $this->permissions = $permissions;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize($this->permissions);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $this->permissions = unserialize($serialized);
    }
}
