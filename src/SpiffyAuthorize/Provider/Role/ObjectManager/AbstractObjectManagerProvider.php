<?php

namespace SpiffyAuthorize\Provider\Role\ObjectManager;

use Doctrine\Common\Persistence\ObjectManager;
use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\AbstractProvider;
use SpiffyAuthorize\Provider\Role\ExtractorTrait;
use SpiffyAuthorize\Provider\Role\ProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;

abstract class AbstractObjectManagerProvider extends AbstractProvider implements ProviderInterface
{
    use ExtractorTrait;
    use ListenerAggregateTrait;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $targetClass;

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
        $this->listeners[] = $events->attach(AuthorizeEvent::EVENT_INIT, [$this, 'load']);
    }

    /**
     * @param AuthorizeEvent $e
     */
    public function load(AuthorizeEvent $e)
    {
        $result = $this->getObjectManager()->getRepository($this->getTargetClass())->findAll();
        $roles  = [];

        foreach ($result as $entity) {
            $role   = $this->extractRole($entity);
            $parent = $this->extractParent($entity);

            if ($parent) {
                $roles[$parent][] = $role;
            } else if (!isset($roles[$role])) {
                $roles[$role] = [];
            }
        }

        $this->loadRoles($e->getTarget(), $roles);
    }

    /**
     * @param \Zend\Permissions\Rbac\Rbac|\Zend\Permissions\Acl\Acl $container
     * @param array $roles
     * @param string|null $parentName
     * @return void
     */
    abstract public function loadRoles($container, $roles, $parentName = null);
}