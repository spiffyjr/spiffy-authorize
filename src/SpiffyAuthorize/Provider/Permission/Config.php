<?php

namespace SpiffyAuthorize\Provider\Permission;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\ProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Loads a configuration based permission map into rbac container.
 *
 * Expected format:
 *
 * [
 *     'permission1' => [ ... roles ... ] (roles can be an array of roles or a string)
 * ]
 *
 * A numeric index will be treated as a role with no children.
 */
class Config implements ProviderInterface
{
    use ListenerAggregateTrait;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac = $e->getTarget();

        foreach ($this->config as $permission => $roles) {
            if (!is_array($roles)) {
                $roles = [ $roles ];
            }

            foreach ($roles as $role) {
                if (!$rbac->hasRole($role)) {
                    continue;
                }
                $rbac->getRole($role)->addPermission($permission);
            }
        }
    }
}