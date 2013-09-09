<?php

namespace SpiffyAuthorize;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $e->getTarget();
        $sm  = $app->getServiceManager();

        foreach ($sm->get('SpiffyAuthorize\Guards') as $guard) {
            $app->getEventManager()->attach($guard);
        }

        $app->getEventManager()->attach($sm->get('SpiffyAuthorize\ViewStrategy'));
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ .'/../../config/module.config.php';
    }
}