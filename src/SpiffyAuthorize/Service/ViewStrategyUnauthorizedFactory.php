<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\View\Strategy\UnauthorizedStrategy;
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
        /** @var \SpiffyAuthorize\Options\ModuleOptions $options */
        $options  = $serviceLocator->get('SpiffyAuthorize\Options\ModuleOptions');
        $strategy = new UnauthorizedStrategy();
        $strategy->setTemplate($options->getViewTemplate());

        return $strategy;
    }
}