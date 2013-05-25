<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Assertion\AssertionInterface;

class SimpleAssertion implements AssertionInterface
{
    public function assert()
    {
        return true;
    }
}