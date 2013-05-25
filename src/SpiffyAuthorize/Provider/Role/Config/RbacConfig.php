<?php

namespace SpiffyAuthorize\Provider\Role\Config;

use SpiffyAuthorize\AuthorizeEvent;
use SpiffyAuthorize\Provider\Role\RbacProviderTrait;

class RbacConfig extends AbstractConfig
{
    use RbacProviderTrait;

    /**
     * @param AuthorizeEvent $e
     */
    public function load(AuthorizeEvent $e)
    {
        $this->loadRoles($e->getTarget(), $this->config);
    }
}