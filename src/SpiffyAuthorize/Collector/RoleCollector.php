<?php

namespace SpiffyAuthorize\Collector;

use Serializable;
use SpiffyAuthorize\Provider\Identity\ProviderInterface;
use SpiffyAuthorize\Provider\Role\ExtractorTrait;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\CollectorInterface;

class RoleCollector implements
    CollectorInterface,
    Serializable
{
    use ExtractorTrait;

    const NAME     = 'spiffy_authorize_role_collector';
    const PRIORITY = 100;

    /**
     * @var ProviderInterface
     */
    protected $identityProvider;

    /**
     * @var array
     */
    protected $roles = [];

    /**
     * @param ProviderInterface $identityProvider
     */
    public function __construct(ProviderInterface $identityProvider)
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
        $cleaned = [];

        foreach ($roles as $role) {
            $cleaned[] = $this->extractRole($role);
        }

        $this->roles = $cleaned;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->roles);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->roles = unserialize($serialized);
    }
}