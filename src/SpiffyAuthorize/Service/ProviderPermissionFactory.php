<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\Options\ModuleOptions;

class ProviderPermissionFactory extends AbstractProviderFactory
{
    /**
     * @param ModuleOptions $options
     * @return array
     */
    protected function getProviders(ModuleOptions $options)
    {
        return $options->getPermissionProviders();
    }
}