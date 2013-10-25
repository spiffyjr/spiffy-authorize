<?php

namespace SpiffyAuthorize\Factory;

use SpiffyAuthorize\View\IsAuthorizedHelper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewHelperIsAuthorizedFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return IsAuthorizedHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\View\HelperPluginManager $serviceLocator */
        $sl = $serviceLocator->getServiceLocator();

        /** @var \SpiffyAuthorize\ModuleOptions $options */
        $options = $sl->get('SpiffyAuthorize\ModuleOptions');
        $service = $sl->get($options->getAuthorizeService());

        return new IsAuthorizedHelper($service);
    }
}
