<?php

namespace SpiffyAuthorize\Provider\Role\Config;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\ProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Loads a configuration based role map into the container.
 *
 * Expected format:
 *
 * [
 *     'parent1' => [ ... children ... ]
 *     'parent2' => [ 'child' => [ ... subchildren ... ] ],
 *     'parent3'
 * ]
 *
 * A numeric index will be treated as a role with no children.
 */
abstract class AbstractConfig implements ProviderInterface
{
    use ListenerAggregateTrait;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
        $events->attach(AuthorizeEvent::EVENT_INIT, [$this, 'load']);
    }

    /**
     * @param AuthorizeEvent $e
     */
    abstract public function load(AuthorizeEvent $e);
}