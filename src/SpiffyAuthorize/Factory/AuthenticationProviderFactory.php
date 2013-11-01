<?php

namespace SpiffyAuthorize\Factory;

use SpiffyAuthorize\Provider\Identity\AuthenticationProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return AuthenticationProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyAuthorize\ModuleOptions $moduleOptions */
        $moduleOptions    = $serviceLocator->get('SpiffyAuthorize\ModuleOptions');
        $identityProvider = new AuthenticationProvider(
            $serviceLocator->get('Zend\Authentication\AuthenticationService')
        );

        // Extract default role and guest role from the config
        if ($guestRole = $moduleOptions->getDefaultGuestRole()) {
            $identityProvider->setDefaultGuestRole($guestRole);
        }

        if ($defaultRole = $moduleOptions->getDefaultRole()) {
            $identityProvider->setDefaultRole($defaultRole);
        }

        return $identityProvider;
    }
}