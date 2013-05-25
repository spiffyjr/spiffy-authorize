<?php

namespace SpiffyAuthorize\Provider\Role\ObjectRepository;

use Doctrine\Common\Persistence\ObjectRepository;
use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\ExtractorTrait;
use SpiffyAuthorize\Provider\Role\ProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;

abstract class AbstractObjectRepository implements ProviderInterface
{
    use ExtractorTrait;
    use ListenerAggregateTrait;

    /**
     * @var ObjectRepository
     */
    protected $objectRepository;

    /**
     * @param ObjectRepository $objectRepository
     */
    public function __construct(ObjectRepository $objectRepository)
    {
        $this->objectRepository = $objectRepository;
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
        $events->attach(AuthorizeEvent::EVENT_INIT, [$this, 'load']);
    }

    /**
     * @param AuthorizeEvent $e
     */
    public function load(AuthorizeEvent $e)
    {
        $result = $this->objectRepository->findAll();
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