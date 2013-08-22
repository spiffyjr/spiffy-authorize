<?php

namespace SpiffyAuthorize\Provider\Identity;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authService = $serviceLocator->get('Zend\Authentication\AuthenticationService');

        $provider = new AuthenticationProvider();
        $provider->setAuthService($authService);

        return $provider;
    }
}