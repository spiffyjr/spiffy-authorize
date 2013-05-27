<?php

namespace SpiffyAuthorize\Provider\Permission\Config;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\AbstractProvider;
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
class RbacProvider extends AbstractProvider implements ProviderInterface
{
    use ListenerAggregateTrait;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @param array $rules
     * @return RbacProvider
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
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
     */
    public function load(AuthorizeEvent $e)
    {
        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac = $e->getTarget();

        foreach ($this->getRules() as $permission => $roles) {
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