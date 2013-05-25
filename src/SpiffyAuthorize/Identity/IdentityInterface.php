<?php

namespace SpiffyAuthorize\Identity;

interface IdentityInterface
{
    /**
     * @return array
     */
    public function getRoles();
}