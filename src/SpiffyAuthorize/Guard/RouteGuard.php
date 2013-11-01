<?php

namespace SpiffyAuthorize\Guard;

use SpiffyAuthorize\Exception\RuntimeException;
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
    /**
     * @var array
     */
    protected $rules = array();

    /**
     * Set the rules
     *
     * @param array $rules
     * @return void
     */
    public function setRules(array $rules)
    {
        $cleanedRules = array();

        foreach ($rules as $route => $permissions) {
            if (is_numeric($permissions)) {
                $route       = $permissions;
                $permissions = array();
            }

            $cleanedRules[$route] = (array) $permissions;
        }

        $this->rules = $cleanedRules;
    }

    /**
     * Get the rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param  MvcEvent $e
     * @throws RuntimeException If regex could not be checked
     * @return void
     */
    public function onRoute(MvcEvent $e)
    {
        if (empty($this->rules)) {
            $e->setParam('guard-result', self::INFO_NO_RULES);
            return;
        }

        $authService = $this->getAuthorizeService();
        $routeMatch  = $e->getRouteMatch();

        $routeName   = $routeMatch->getMatchedRouteName();
        $isMatch     = false;
        $resources   = array();

        foreach (array_keys($this->rules) as $routeRegex) {
            $result = preg_match("/{$routeRegex}/", $routeName);

            if (false === $result) {
                throw new RuntimeException(sprintf(
                    'Unable to test regex: "%s"',
                    $routeRegex
                ));
            } elseif ($result) {
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
