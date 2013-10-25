<?php

namespace SpiffyAuthorize\Factory;

use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProviderIdentityFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $provider = new AuthenticationProvider();
        $provider->setAuthService($serviceLocator->get('Zend\Authentication\AuthenticationService'));

        return $provider;
    }
}
