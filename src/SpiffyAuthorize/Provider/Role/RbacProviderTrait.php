<?php

namespace SpiffyAuthorize\Provider\Role;

use Zend\Permissions\Rbac\Rbac;

trait RbacProviderTrait
{
    /**
     * Recursive method to add roles.
     *
     * @param Rbac $rbac
     * @param $roles
     * @param string|null $parentName
     * @return void
     */
    protected function loadRoles(Rbac $rbac, $roles, $parentName = null)
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
