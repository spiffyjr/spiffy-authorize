<?php

namespace SpiffyAuthorize\Service;

interface AuthorizeInterface
{
    /**
     * Return the container that is used for authorization.
     *
     * @return mixed
     */
    public function getContainer();

    /**
     * Check if authorization is allowed for a given permission.
     *
     * @param string $resource
     * @param null|string|\Closure\\SpiffyAuthorize\Assertion\AssertionInterface $assertion
     * @throws \SpiffyAuthorize\Assertion\Exception\InvalidArgumentException on invalid assertion type
     * @return bool
     */
    public function isAuthorized($resource, $assertion = null);

    /**
     * Check if a role is known.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role);

    /**
     * Check if a permission is known.
     *
     * @param string $resource
     * @return bool
     */
    public function hasResource($resource);

    /**
     * Registers an assertion to be checked with isAuthorized for the given permission.
     *
     * @param string $resource
     * @param string|\Closure\\SpiffyAuthorize\Assertion\AssertionInterface $assertion
     * @throws \SpiffyAuthorize\Assertion\Exception\InvalidArgumentException on invalid assertion type
     * @return AuthorizeInterface
     */
    public function registerAssertion($resource, $assertion);
}