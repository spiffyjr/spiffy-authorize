<?php

namespace SpiffyAuthorize\Guard;

use SpiffyAuthorize\Service\AuthorizeServiceAwareTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\AbstractOptions;

abstract class AbstractGuard extends AbstractOptions implements GuardInterface
{
    use AuthorizeServiceAwareTrait;
    use ListenerAggregateTrait;

    const INFO_AUTHORIZED          = 'info-authorized';
    const INFO_NO_RULES            = 'info-no-rules';
    const INFO_UNKNOWN_ROUTE       = 'info-unknown-route';
    const ERROR_UNAUTHORIZED_ROUTE = 'error-unauthorized-route';
    const RESOURCE_PREFIX          = 'route-';

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute']);
    }
}