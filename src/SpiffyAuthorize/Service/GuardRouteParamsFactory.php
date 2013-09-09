<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\Guard\RouteParamsGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GuardRouteParamsFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyAuthorize\ModuleOptions $options */
        $options = $serviceLocator->get('SpiffyAuthorize\ModuleOptions');
        $guard   = new RouteParamsGuard();
        $guard->setAuthorizeService($serviceLocator->get($options->getAuthorizeService()));

        return $guard;
    }
}