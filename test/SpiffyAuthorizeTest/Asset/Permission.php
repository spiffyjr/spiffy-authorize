<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Permission\PermissionInterface;

class Permission implements PermissionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @param string $name
     * @param array $roles
     */
    public function __construct($name, array $roles)
    {
        $this->name  = $name;
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}