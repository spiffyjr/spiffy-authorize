<?php

namespace SpiffyAuthorize\Provider\Permission\Config;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\ProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Stdlib\AbstractOptions;

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
class RbacProvider extends AbstractOptions implements ProviderInterface
{
    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

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
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(AuthorizeEvent::EVENT_INIT, array($this, 'load'), -100);
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
                $roles = array($roles);
            }

            foreach ($roles as $role) {
                if (!$rbac->hasRole($role)) {
                    continue;
                }
                $rbac->getRole($role)->addPermission($permission);
            }
        }
    }

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
}