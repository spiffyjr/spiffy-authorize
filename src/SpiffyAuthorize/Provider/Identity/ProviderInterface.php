<?php

namespace SpiffyAuthorize\Provider\Identity;

interface ProviderInterface
{
    /**
     * Gets the roles for the identity.
     *
     * @return array
     */
    public function getIdentityRoles();

    /**
     * Get the default role for no identity.
     *
     * @return string
     */
    public function getDefaultUnauthorizedRole();

    /**
     * Get the default role for an identity with no roles available or set.
     *
     * @return string
     */
    public function getDefaultAuthorizedRole();
}