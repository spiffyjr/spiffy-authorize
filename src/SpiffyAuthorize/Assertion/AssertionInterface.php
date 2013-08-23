<?php

namespace SpiffyAuthorize\Assertion;

interface AssertionInterface
{
    /**
     * Assert that the identity has access to the resource
     *
     * @return bool
     */
    public function assert();
}
