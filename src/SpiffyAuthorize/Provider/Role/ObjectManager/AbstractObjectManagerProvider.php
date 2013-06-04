<?php

namespace SpiffyAuthorize\Provider\Role\ObjectManager;

use Doctrine\Common\Persistence\ObjectManager;
use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\ProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Permissions\Acl;
use Zend\Permissions\Rbac;
use Zend\Stdlib\AbstractOptions;

abstract class AbstractObjectManagerProvider extends AbstractOptions implements ProviderInterface
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
     * @param ObjectManager $objectManager
     * @return AbstractObjectManagerProvider
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
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
     * @return AbstractObjectManagerProvider
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
        $this->listeners[] = $events->attach(AuthorizeEvent::EVENT_INIT, array($this, 'load'));
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
     * @param AuthorizeEvent $e
     */
    public function load(AuthorizeEvent $e)
    {
        $result = $this->getObjectManager()->getRepository($this->getTargetClass())->findAll();
        $roles  = array();

        foreach ($result as $entity) {
            $role   = $this->extractRole($entity);
            $parent = $this->extractParent($entity);

            if ($parent) {
                $roles[$parent][] = $role;
            } else if (!isset($roles[$role])) {
                $roles[$role] = array();
            }
        }

        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac = $e->getTarget();
        $this->loadRoles($rbac, $roles);
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
     * @param \Zend\Permissions\Rbac\Rbac|\Zend\Permissions\Acl\Acl $container
     * @param array $roles
     * @param string|null $parentName
     * @return void
     */
    abstract protected function loadRoles($container, $roles, $parentName = null);
}
