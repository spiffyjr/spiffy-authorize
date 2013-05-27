<?php

namespace SpiffyAuthorize\Service;

use SpiffyAuthorize\Options\ModuleOptions;

class GuardFactory extends AbstractInstanceFactory
{
    /**
     * @param ModuleOptions $options
     * @return array
     */
    protected function getInstances(ModuleOptions $options)
    {
        return $options->getGuards();
    }
}