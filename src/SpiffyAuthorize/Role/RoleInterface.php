<?php

namespace SpiffyAuthorize\Role;

interface RoleInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getChildren();

    /**
     * @return array
     */
    public function getParent();
}