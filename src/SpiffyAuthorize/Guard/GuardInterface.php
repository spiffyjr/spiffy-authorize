<?php

namespace SpiffyAuthorize\Guard;

use Zend\EventManager\ListenerAggregateInterface;

interface GuardInterface extends ListenerAggregateInterface
{
    /**
     * @param array $rules
     * @return RouteGuard
     */
    public function setRules(array $rules);

    /**
     * @return array
     */
    public function getRules();
}