<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Identity\IdentityInterface;

class Identity implements IdentityInterface
{
    /**
     * @return array
     */
    public function getRoles()
    {
        return array('role1');
    }
}