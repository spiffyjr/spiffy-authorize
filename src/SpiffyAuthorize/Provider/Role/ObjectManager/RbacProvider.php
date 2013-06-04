<?php

namespace SpiffyAuthorize\Provider\Role\ObjectManager;

class RbacProvider extends AbstractObjectManagerProvider
{
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