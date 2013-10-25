<?php

namespace SpiffyAuthorize\Factory;

use SpiffyAuthorize\Collector\PermissionCollector;
use SpiffyAuthorize\Guard\RouteGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CollectorPermissionFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return PermissionCollector
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyAuthorize\ModuleOptions $options */
        $options = $serviceLocator->get('SpiffyAuthorize\ModuleOptions');
        $service = $serviceLocator->get($options->getAuthorizeService());

        return new PermissionCollector($service);
    }
}
