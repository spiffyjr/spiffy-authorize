<?php

namespace SpiffyAuthorize\Factory;

use SpiffyAuthorize\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OptionsModuleFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ModuleOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        return new ModuleOptions(isset($config['spiffy_authorize']) ? $config['spiffy_authorize'] : []);
    }
}
