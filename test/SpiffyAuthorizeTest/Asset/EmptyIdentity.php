<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Identity\IdentityInterface;

class EmptyIdentity implements IdentityInterface
{
    /**
     * @return array
     */
    public function getRoles()
    {
        return [];
    }
}