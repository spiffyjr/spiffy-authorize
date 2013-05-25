<?php

namespace SpiffyAuthorize\Provider\Permission;

use Doctrine\Common\Persistence;
use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Permission\PermissionInterface;
use SpiffyAuthorize\Provider\Role\ExtractorTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;

class ObjectRepository implements ProviderInterface
{
    use ExtractorTrait;
    use ListenerAggregateTrait;

    /**
     * @var Persistence\ObjectRepository
     */
    protected $objectRepository;

    /**
     * @param Persistence\ObjectRepository $objectRepository
     */
    public function __construct(Persistence\ObjectRepository $objectRepository)
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
     * @throws Exception\InvalidArgumentException if permissions are an array with invalid format
     */
    public function load(AuthorizeEvent $e)
    {
        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac   = $e->getTarget();
        $result = $this->objectRepository->findAll();

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
                    throw new Exception\InvalidArgumentException(
                        'roles provided with no permission name'
                    );
                }

                if (!is_array($roles)) {
                    $roles = [ $roles ];
                }
            } else {
                throw new Exception\InvalidArgumentException('unknown permission entity type');
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