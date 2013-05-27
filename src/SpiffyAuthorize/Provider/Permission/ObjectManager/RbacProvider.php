<?php

namespace SpiffyAuthorize\Provider\Permission\ObjectManager;

use Doctrine\Common\Persistence\ObjectManager;
use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Permission\PermissionInterface;
use SpiffyAuthorize\Provider\AbstractProvider;
use SpiffyAuthorize\Provider\Permission;
use SpiffyAuthorize\Provider\Role;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;

class RbacProvider extends AbstractProvider implements Permission\ProviderInterface
{
    use ListenerAggregateTrait;
    use Role\ExtractorTrait;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $targetClass;

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
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(AuthorizeEvent::EVENT_INIT, [$this, 'load'], -100);
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
            $roles      = [];

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
                    $roles = [ $roles ];
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