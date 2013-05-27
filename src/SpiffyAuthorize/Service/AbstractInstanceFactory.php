<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractInstanceFactory implements FactoryInterface
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
        $instances = [];

        foreach ($this->getInstances($options) as $config) {
            $instance = $this->get($serviceLocator, $config['name']);
            $instance->setFromArray(isset($config['options']) ? $config['options'] : []);

            $instances[] = $instance;
        }

        return $instances;
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
    abstract protected function getInstances(ModuleOptions $options);
}