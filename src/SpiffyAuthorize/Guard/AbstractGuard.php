<?php

namespace SpiffyAuthorize\Guard;

use SpiffyAuthorize\Service\AuthorizeServiceInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\AbstractOptions;

abstract class AbstractGuard extends AbstractOptions implements GuardInterface
{
    const INFO_AUTHORIZED          = 'info-authorized';
    const INFO_NO_RULES            = 'info-no-rules';
    const INFO_UNKNOWN_ROUTE       = 'info-unknown-route';
    const ERROR_UNAUTHORIZED_ROUTE = 'error-unauthorized-route';
    const RESOURCE_PREFIX          = 'route-';

    /**
     * @var AuthorizeServiceInterface
     */
    protected $authorizeService;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @param AuthorizeServiceInterface $authorizeService
     * @return mixed
     */
    public function setAuthorizeService(AuthorizeServiceInterface $authorizeService)
    {
        $this->authorizeService = $authorizeService;
        return $this;
    }

    /**
     * @return \SpiffyAuthorize\Service\AuthorizeServiceInterface
     */
    public function getAuthorizeService()
    {
        return $this->authorizeService;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    }
}