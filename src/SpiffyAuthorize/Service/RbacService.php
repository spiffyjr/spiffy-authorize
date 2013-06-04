<?php

namespace SpiffyAuthorize\Service;

use Traversable;
use RecursiveIteratorIterator;
use SpiffyAuthorize\Assertion;
use SpiffyAuthorize\Provider\Identity\ProviderInterface as IdentityProviderInterface;
use SpiffyAuthorize\AuthorizeEvent;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Permissions;

class RbacService implements AuthorizeServiceInterface
{
    /**
     * @var array
     */
    protected $assertions = array();

    /**
     * @var IdentityProviderInterface
     */
    protected $identityProvider;

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * @var Permissions\Rbac\Rbac
     */
    protected $container;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(__CLASS__, get_class($this));
        if (isset($this->eventIdentifier)) {
            if ((is_string($this->eventIdentifier))
                || (is_array($this->eventIdentifier))
                || ($this->eventIdentifier instanceof Traversable)
            ) {
                $identifiers = array_unique(array_merge($identifiers, (array) $this->eventIdentifier));
            } elseif (is_object($this->eventIdentifier)) {
                $identifiers[] = $this->eventIdentifier;
            }
            // silently ignore invalid eventIdentifier types
        }
        $events->setIdentifiers($identifiers);
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    /**
     * Check if authorization is allowed for a given permission.
     *
     * @param string $permission
     * @param null|string|\Closure\\SpiffyAuthorize\Assertion\AssertionInterface $assertion
     * @throws \SpiffyAuthorize\Assertion\Exception\InvalidArgumentException on invalid assertion type
     * @return bool
     */
    public function isAuthorized($permission, $assertion = null)
    {
        if (!$assertion && isset($this->assertions[$assertion])) {
            $assertion = $this->assertions[$assertion];
        }

        if ($assertion) {
            if ($assertion instanceof Assertion\AssertionInterface) {
                if (!$assertion->assert()) {
                    return false;
                }
            } else {
                if (!call_user_func($assertion)) {
                    return false;
                }
            }
        }

        return $this->hasResource($permission);
    }

    /**
     * Check if a role is known. Does most performant checks first.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        if (!$this->getIdentityProvider()) {
            return false;
        }

        foreach ($this->getIdentityProvider()->getIdentityRoles() as $identityRole) {
            if ($identityRole === $role) {
                return true;
            }

            try {
                $it = new RecursiveIteratorIterator(
                    $this->getContainer()->getRole($identityRole),
                    RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($it as $leaf) {
                    /** @var \Zend\Permissions\Rbac\RoleInterface $leaf */
                    if ($leaf->getName() == $role) {
                        return true;
                    }
                }
            } catch (Permissions\Rbac\Exception\InvalidArgumentException $e) {
            }
        }
        return false;
    }

    /**
     * Check if a permission is known. Does most performant checks first.
     *
     * @param string $permission
     * @return bool
     */
    public function hasResource($permission)
    {
        $provider = $this->getIdentityProvider();
        if (!$provider) {
            return false;
        }

        $container = $this->getContainer();
        foreach ($provider->getIdentityRoles() as $identityRole) {
            if (!$container->hasRole($identityRole)) {
                return false;
            }

            if ($container->getRole($identityRole)->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Registers an assertion to be checked with isAuthorized for the given permission.
     *
     * @param string $permission
     * @param string|\Closure\\SpiffyAuthorize\Assertion\AssertionInterface $assertion
     * @throws \SpiffyAuthorize\Assertion\Exception\InvalidArgumentException on invalid assertion type
     * @return RbacService
     */
    public function registerAssertion($permission, $assertion)
    {
        if (!is_callable($assertion) && !$assertion instanceof Assertion\AssertionInterface) {
            throw new Assertion\Exception\InvalidArgumentException('unknown assertion type');
        }
        $this->assertions[$permission] = $assertion;
        return $this;
    }

    /**
     * @return RbacService
     */
    public function clearAssertions()
    {
        $this->assertions = array();
        return $this;
    }

    /**
     * @param array $assertions
     * @return RbacService
     */
    public function setAssertions($assertions)
    {
        $this->clearAssertions();

        foreach($assertions as $permission => $assertion) {
            $this->registerAssertion($permission, $assertion);
        }
        $this->assertions = $assertions;
        return $this;
    }

    /**
     * @return array
     */
    public function getAssertions()
    {
        return $this->assertions;
    }

    /**
     * @return \Zend\Permissions\Rbac\Rbac
     */
    public function getContainer()
    {
        if (!$this->container) {
            $this->container = new Permissions\Rbac\Rbac();

            $event = new AuthorizeEvent();
            $event->setAuthorizeService($this);
            $event->setTarget($this->container);

            $this->getEventManager()->trigger(AuthorizeEvent::EVENT_INIT, $event);
        }
        return $this->container;
    }

    /**
     * @param \SpiffyAuthorize\Provider\Identity\ProviderInterface $identityProvider
     * @return RbacService
     */
    public function setIdentityProvider($identityProvider)
    {
        $this->identityProvider = $identityProvider;
        return $this;
    }

    /**
     * @return \SpiffyAuthorize\Provider\Identity\ProviderInterface
     */
    public function getIdentityProvider()
    {
        return $this->identityProvider;
    }

    /**
     * Setting a new provider requires a reset of rbac roles/permissions.
     *
     * @return RbacService
     */
    protected function reset()
    {
        $this->container = null;
        return $this;
    }
}