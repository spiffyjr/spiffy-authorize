<?php

namespace SpiffyAuthorize\Guard;

use SpiffyAuthorize\Exception\RuntimeException;
use Zend\Mvc\MvcEvent;

/**
 * A guard that checks for a 'permissions' param for the matched route
 */
class RouteParamsGuard extends AbstractGuard
{
    /**
     * @param  MvcEvent $e
     * @throws RuntimeException If regex could not be checked
     */
    public function onRoute(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        $resources  = $routeMatch->getParam('resources');

        if (!$resources) {
            return;
        }

        foreach ($resources as $resource) {
            if ($this->getAuthorizeService()->isAuthorized($resource)) {
                $e->setParam('guard-result', self::INFO_AUTHORIZED);
                $e->setParam('guard-resource', $resource);
                return;
            }
        }

        $e->setError(self::ERROR_UNAUTHORIZED_ROUTE);
        $e->setParam('route', $routeMatch->getMatchedRouteName());

        /* @var $app \Zend\Mvc\Application */
        $app = $e->getTarget();
        $app->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
    }
}
