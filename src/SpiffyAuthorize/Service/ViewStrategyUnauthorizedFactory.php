<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\View\UnauthorizedStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewStrategyUnauthorizedFactory implements FactoryInterface
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
        $options  = $serviceLocator->get('SpiffyAuthorize\ModuleOptions');
        $strategy = new UnauthorizedStrategy();
        $strategy->setTemplate($options->getViewTemplate());

        return $strategy;
    }
}