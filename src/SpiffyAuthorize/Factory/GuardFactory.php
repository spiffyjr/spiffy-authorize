<?php

namespace SpiffyAuthorize\Factory;

use SpiffyAuthorize\ModuleOptions;

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
