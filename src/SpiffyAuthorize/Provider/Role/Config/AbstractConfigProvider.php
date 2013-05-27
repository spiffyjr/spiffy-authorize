<?php

namespace SpiffyAuthorize\Provider\Role\Config;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\AbstractProvider;
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
abstract class AbstractConfigProvider extends AbstractProvider implements ProviderInterface
{
    use ListenerAggregateTrait;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @param array $rules
     * @return AbstractConfigProvider
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
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
        $this->listeners[] = $events->attach(AuthorizeEvent::EVENT_INIT, [$this, 'load']);
    }

    /**
     * @param AuthorizeEvent $e
     */
    abstract public function load(AuthorizeEvent $e);
}