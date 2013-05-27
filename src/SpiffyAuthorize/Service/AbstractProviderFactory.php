<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractProviderFactory implements FactoryInterface
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
        $options   = $serviceLocator->get('SpiffyAuthorize\Options\ModuleOptions');
        $providers = [];

        foreach ($this->getProviders($options) as $providerConfig) {
            /** @var \SpiffyAuthorize\Provider\AbstractProvider $provider */
            $provider = $this->get($serviceLocator, $providerConfig['name']);
            $provider->setFromArray(isset($providerConfig['options']) ? $providerConfig['options'] : []);

            $providers[] = $provider;
        }

        return $providers;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @return object
     */
    protected function get(ServiceLocatorInterface $serviceLocator, $name)
    {
        if (is_string($name) && $serviceLocator->has($name)) {
            return $serviceLocator->get($name);
        }
        return new $name;
    }

    /**
     * @param ModuleOptions $options
     * @return array
     */
    abstract protected function getProviders(ModuleOptions $options);
}