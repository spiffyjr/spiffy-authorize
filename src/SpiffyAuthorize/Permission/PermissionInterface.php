<?php

namespace SpiffyAuthorize\Permission;

interface PermissionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getRoles();
}