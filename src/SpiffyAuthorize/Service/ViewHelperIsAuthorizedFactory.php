<?php

namespace SpiffyAuthorize\Service;

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
        /** @var \SpiffyAuthorize\ModuleOptions $options */
        $options = $serviceLocator->get('SpiffyAuthorize\ModuleOptions');
        $service = $serviceLocator->get($options->getAuthorizeService());

        return new IsAuthorizedHelper($service);
    }
}