<?php

namespace SpiffyAuthorize\Provider\Role\Config;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\ProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\AbstractOptions;

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
abstract class AbstractConfigProvider extends AbstractOptions implements ProviderInterface
{
    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

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
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(AuthorizeEvent::EVENT_INIT, array($this, 'load'));
    }

    /**
     * @param AuthorizeEvent $e
     */
    abstract public function load(AuthorizeEvent $e);
}