<?php

namespace SpiffyAuthorize\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RbacServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $perms = $serviceLocator->get('SpiffyAuthorize\PermissionProviders');
        $roles = $serviceLocator->get('SpiffyAuthorize\RoleProviders');
    }
}