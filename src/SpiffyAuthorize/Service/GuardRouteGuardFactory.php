<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\Guard\RouteGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GuardRouteGuardFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyAuthorize\Options\ModuleOptions $options */
        $options = $serviceLocator->get('SpiffyAuthorize\Options\ModuleOptions');
        $guard   = new RouteGuard();
        $guard->setAuthorizeService($serviceLocator->get($options->getAuthorizeService()));

        return $guard;
    }
}