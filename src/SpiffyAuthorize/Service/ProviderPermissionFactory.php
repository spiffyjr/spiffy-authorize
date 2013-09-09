<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\ModuleOptions;

class ProviderPermissionFactory extends AbstractInstanceFactory
{
    /**
     * @param ModuleOptions $options
     * @return array
     */
    protected function getInstances(ModuleOptions $options)
    {
        return $options->getPermissionProviders();
    }
}