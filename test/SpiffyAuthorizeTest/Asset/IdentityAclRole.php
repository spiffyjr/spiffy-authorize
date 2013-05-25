<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Identity\IdentityInterface;
use Zend\Permissions\Acl\Role\GenericRole;

class IdentityAclRole implements IdentityInterface
{
    /**
     * @return array
     */
    public function getRoles()
    {
        return [
            new GenericRole('foo'),
            new GenericRole('bar')
        ];
    }
}