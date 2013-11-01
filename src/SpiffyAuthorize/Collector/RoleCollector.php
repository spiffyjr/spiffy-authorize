<?php

namespace SpiffyAuthorize\Collector;

use Serializable;
use SpiffyAuthorize\Provider\Identity\IdentityProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl;
use Zend\Permissions\Rbac;
use ZendDeveloperTools\Collector\CollectorInterface;

/**
 * todo: implement ExtractorTrait in PHP 5.4
 */
class RoleCollector implements
    CollectorInterface,
    Serializable
{
    const NAME     = 'spiffy_authorize_role_collector';
    const PRIORITY = 100;

    /**
     * @var IdentityProviderInterface
     */
    protected $identityProvider;

    /**
     * @var array
     */
    protected $roles = array();

    /**
     * @param IdentityProviderInterface $identityProvider
     */
    public function __construct(IdentityProviderInterface $identityProvider)
    {
        $this->identityProvider = $identityProvider;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Collector Name.
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Collector Priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return self::PRIORITY;
    }

    /**
     * Collects data.
     *
     * @param MvcEvent $mvcEvent
     */
    public function collect(MvcEvent $mvcEvent)
    {
        if (!$this->identityProvider) {
            return;
        }

        $roles   = $this->identityProvider->getIdentityRoles();
        $cleaned = array();

        foreach ($roles as $role) {
            $cleaned[] = $this->extractRole($role);
        }

        $this->roles = $cleaned;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize($this->roles);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $this->roles = unserialize($serialized);
    }

    /**
     * Examines an entity and extracts the role if one is available.
     *
     * @param  object|mixed $entity
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
     *
     * @param  object|string $entity
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
}
