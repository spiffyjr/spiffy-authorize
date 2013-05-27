<?php

namespace SpiffyAuthorize\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GuardFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('SpiffyAuthorize\Options\ModuleOptions');
        $guards  = [];

        foreach ($options->getGuards() as $guardConfig) {
            /** @var \SpiffyAuthorize\Guard\GuardInterface $guard */
            $guard = new $guardConfig['name'];
            $guard->setRules($guardConfig['rules']);

            $guards[] = $guard;
        }

        return $guards;
    }
}