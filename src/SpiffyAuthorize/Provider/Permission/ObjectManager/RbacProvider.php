<?php

namespace SpiffyAuthorize\Provider\Permission\ObjectManager;

use Doctrine\Common\Persistence\ObjectManager;
use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Permission\PermissionInterface;
use SpiffyAuthorize\Provider\Permission;
use SpiffyAuthorize\Provider\Role;
use SpiffyAuthorize\Role\RoleInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Permissions\Acl;
use Zend\Permissions\Rbac;
use Zend\Stdlib\AbstractOptions;

class RbacProvider extends AbstractOptions implements Permission\ProviderInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $targetClass;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @param ObjectManager $ObjectManager
     * @return RbacProvider
     */
    public function setObjectManager(ObjectManager $ObjectManager)
    {
        $this->objectManager = $ObjectManager;
        return $this;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param string $targetClass
     * @return RbacProvider
     */
    public function setTargetClass($targetClass)
    {
        $this->targetClass = $targetClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetClass()
    {
        return $this->targetClass;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(AuthorizeEvent::EVENT_INIT, array($this, 'load'), -100);
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

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
        } else if ($entity instanceof RoleInterface) {
            return $entity->getName();
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

    /**
     * @param AuthorizeEvent $e
     * @throws Permission\Exception\InvalidArgumentException if permissions are an array with invalid format
     * @throws Permission\Exception\RuntimeException if no object manager was set
     * @throws Permission\Exception\RuntimeException if no target class was set
     */
    public function load(AuthorizeEvent $e)
    {
        if (!$this->getObjectManager()) {
            throw new Permission\Exception\RuntimeException('No object_manager was set.');
        }

        if (!$this->getTargetClass()) {
            throw new Permission\Exception\RuntimeException('No target_class was set.');
        }

        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac   = $e->getTarget();
        $result = $this->getObjectManager()->getRepository($this->getTargetClass())->findAll();

        foreach ($result as $entity) {
            $permission = null;
            $roles      = array();

            if ($entity instanceof PermissionInterface) {
                $permission = $entity->getName();
                $roles      = $entity->getRoles();
            } else if (is_array($entity)) {
                $permission = key($entity);
                $roles      = current($entity);

                if (is_numeric($permission)) {
                    throw new Permission\Exception\InvalidArgumentException(
                        'roles provided with no permission name'
                    );
                }

                if (!is_array($roles)) {
                    $roles = array($roles);
                }
            } else {
                throw new Permission\Exception\InvalidArgumentException('unknown permission entity type');
            }

            foreach ($roles as $role) {
                $roleName = $this->extractRole($role);

                if ($roleName) {
                    $rbac->getRole($roleName)
                         ->addPermission($permission);
                }
            }
        }
    }
}