<?php

namespace SpiffyAuthorize\Provider\Identity;

/**
 * An identity provider is responsible to return the current identity role
 */
interface IdentityProviderInterface
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
    public function getDefaultGuestRole();

    /**
     * Get the default role for an identity with no roles available or set.
     *
     * @return string
     */
    public function getDefaultRole();
}
