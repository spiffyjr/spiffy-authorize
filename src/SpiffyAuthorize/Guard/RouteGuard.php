<?php

namespace SpiffyAuthorize\Guard;

use SpiffyAuthorize\Exception\RuntimeException;
use SpiffyAuthorize\Service\AuthorizeServiceAwareTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;

/**
 * A guard for routes. Rules should be as follows:
 *
 *     [
 *         'route' => [ 'permission1', 'permission2' ] // grand permission1 and permission2 access to route
 *         'route2' // deny access from everyone to route2
 *     ]
 *
 * Route is a regular expression! No route definition means passthru!
 */
class RouteGuard extends AbstractGuard
{
    use AuthorizeServiceAwareTrait;
    use ListenerAggregateTrait;

    const INFO_AUTHORIZED          = 'info-authorized';
    const INFO_NO_RULES            = 'info-no-rules';
    const INFO_UNKNOWN_ROUTE       = 'info-unknown-route';
    const ERROR_UNAUTHORIZED_ROUTE = 'error-unauthorized-route';
    const RESOURCE_PREFIX          = 'route-';

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @param array $rules
     * @return RouteGuard
     */
    public function setRules(array $rules)
    {
        $cleaned = [];

        foreach ($rules as $route => $permissions) {
            if (is_numeric($permissions)) {
                $route       = $permissions;
                $permissions = [];
            }

            if (!is_array($permissions)) {
                $permissions = [ $permissions ];
            }

            $cleaned[$route] = $permissions;
        }
        $this->rules = $cleaned;
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute']);
    }

    /**
     * @param MvcEvent $e
     * @throws RuntimeException if regex could not be checked
     */
    public function onRoute(MvcEvent $e)
    {
        if (0 === count($this->getRules())) {
            $e->setParam('guard-result', self::INFO_NO_RULES);
            return;
        }

        $authService = $this->getAuthorizeService();
        $routeMatch  = $e->getRouteMatch();
        $routeName   = $routeMatch->getMatchedRouteName();
        $isMatch     = false;
        $resources   = [];

        foreach (array_keys($this->rules) as $routeRegex) {
            $result = @preg_match("/{$routeRegex}/", $routeName);

            if (false === $result) {
                throw new RuntimeException(sprintf(
                    'unable to test regex: "%s"',
                    $routeRegex
                ));
            } else if ($result) {
                $resources = $this->rules[$routeRegex];
                $isMatch   = true;
                break;
            }
        }

        if (!$isMatch) {
            $e->setParam('guard-result', self::INFO_UNKNOWN_ROUTE);
            return;
        }

        foreach ($resources as $resource) {
            if ($authService->isAuthorized($resource)) {
                $e->setParam('guard-result', self::INFO_AUTHORIZED);
                $e->setParam('guard-resource', $resource);
                return;
            }
        }

        $e->setError(self::ERROR_UNAUTHORIZED_ROUTE);
        $e->setParam('route', $routeName);

        /* @var $app \Zend\Mvc\Application */
        $app = $e->getTarget();
        $app->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
    }
}