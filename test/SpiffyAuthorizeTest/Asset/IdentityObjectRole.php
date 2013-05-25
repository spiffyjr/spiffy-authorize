<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Identity\IdentityInterface;

class ObjectRole
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function __toString()
    {
        return $this->role;
    }
}

class IdentityObjectRole implements IdentityInterface
{
    /**
     * @return array
     */
    public function getRoles()
    {
        return [
            new ObjectRole('foo'),
            new ObjectRole('bar')
        ];
    }
}