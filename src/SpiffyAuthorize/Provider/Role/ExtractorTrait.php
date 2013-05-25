<?php

namespace SpiffyAuthorize\Provider\Role;

use Zend\Permissions\Acl;
use Zend\Permissions\Rbac;

trait ExtractorTrait
{
    /**
     * Examines an entity and extracts the role if one is available.
     * @param $entity
     * @return null|string
     */
    public function extractRole($entity)
    {
        if ($entity instanceof Rbac\RoleInterface) {
            return $entity->getName();
        } else if ($entity instanceof Acl\Role\RoleInterface) {
            // todo: implement me
        } else if (is_string($entity)) {
            return $entity;
        }
        return null;
    }

    /**
     * Examines an entity and extracts the parent if one is available.
     * @param $entity
     * @return null|string
     */
    public function extractParent($entity)
    {
        if ($entity instanceof Rbac\RoleInterface) {
            return $entity->getParent() ? $entity->getParent()->getName() : null;
        } else if ($entity instanceof Acl\Role\RoleInterface) {
            // todo: implement me
        } else if (is_string($entity)) {
            return null;
        }
        return null;
    }
}