<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\Options\ModuleOptions;

class ProviderRoleFactory extends AbstractProviderFactory
{
    /**
     * @param ModuleOptions $options
     * @return array
     */
    protected function getProviders(ModuleOptions $options)
    {
        return $options->getRoleProviders();
    }
}