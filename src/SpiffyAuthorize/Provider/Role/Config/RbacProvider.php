<?php

namespace SpiffyAuthorize\Provider\Role\Config;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\RbacProviderTrait;

class RbacProvider extends AbstractConfigProvider
{
    /**
     * @param AuthorizeEvent $e
     */
    public function load(AuthorizeEvent $e)
    {
        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac = $e->getTarget();

        $this->loadRoles($rbac, $this->getRules());
    }

    /**
     * Recursive method to add roles.
     *
     * @param \Zend\Permissions\Rbac\Rbac $rbac
     * @param $roles
     * @param string|null $parentName
     * @return void
     */
    protected function loadRoles($rbac, $roles, $parentName = null)
    {
        foreach ($roles as $parent => $children) {
            if (is_numeric($parent)) {
                $parent   = $children;
                $children = array();
            }

            if ($parentName) {
                $rbac->getRole($parentName)->addChild($parent);
            } else if (!$rbac->hasRole($parent)) {
                $rbac->addRole($parent);
            }

            $this->loadRoles($rbac, $children, $parent);
        }
    }
}