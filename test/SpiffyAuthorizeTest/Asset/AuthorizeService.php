<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Service\AuthorizeServiceInterface;

class AuthorizeService implements AuthorizeServiceInterface
{
    protected $resources = ['foo', 'really-allowed', 'route-foo'];

    /**
     * Return the container that is used for authorization.
     *
     * @return mixed
     */
    public function getContainer()
    {
        return [];
    }

    /**
     * Check if authorization is allowed for a given permission.
     *
     * @param string $resource
     * @param null|string|\Closure\\SpiffyAuthorize\Assertion\AssertionInterface $assertion
     * @throws \SpiffyAuthorize\Assertion\Exception\InvalidArgumentException on invalid assertion type
     * @return bool
     */
    public function isAuthorized($resource, $assertion = null)
    {
        return in_array($resource, $this->resources);
    }

    /**
     * Check if a role is known.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, ['role1']);
    }

    /**
     * Check if a permission is known.
     *
     * @param string $resource
     * @return bool
     */
    public function hasResource($resource)
    {
        return in_array($resource, $this->resources);
    }

    /**
     * Registers an assertion to be checked with isAuthorized for the given permission.
     *
     * @param string $resource
     * @param string|\Closure\\SpiffyAuthorize\Assertion\AssertionInterface $assertion
     * @throws \SpiffyAuthorize\Assertion\Exception\InvalidArgumentException on invalid assertion type
     * @return AuthorizeServiceInterface
     */
    public function registerAssertion($resource, $assertion)
    {}
}