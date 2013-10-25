<?php

namespace SpiffyAuthorize\Factory;

use SpiffyAuthorize\Collector\RoleCollector;
use SpiffyAuthorize\Guard\RouteGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CollectorRoleFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return RoleCollector
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyAuthorize\ModuleOptions $options */
        $options  = $serviceLocator->get('SpiffyAuthorize\ModuleOptions');
        $provider = $serviceLocator->get($options->getIdentityProvider());

        return new RoleCollector($provider);
    }
}
