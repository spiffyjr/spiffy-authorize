<?php

namespace SpiffyAuthorize\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewStrategyFactory implements FactoryInterface
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

        return $serviceLocator->get($options->getViewStrategy());
    }
}
