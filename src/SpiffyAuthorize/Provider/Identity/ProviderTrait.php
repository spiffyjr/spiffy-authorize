<?php

namespace SpiffyAuthorize\Provider\Identity;

trait ProviderTrait
{
    /**
     * @var string
     */
    protected $defaultUnauthorizedRole = 'guest';

    /**
     * @var string
     */
    protected $defaultAuthorizedRole = 'member';

    /**
     * @param string $defaultAuthorizedRole
     * @return ProviderTrait
     */
    public function setDefaultAuthorizedRole($defaultAuthorizedRole)
    {
        $this->defaultAuthorizedRole = $defaultAuthorizedRole;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultAuthorizedRole()
    {
        return $this->defaultAuthorizedRole;
    }

    /**
     * @param string $defaultUnauthorizedRole
     * @return ProviderTrait
     */
    public function setDefaultUnauthorizedRole($defaultUnauthorizedRole)
    {
        $this->defaultUnauthorizedRole = $defaultUnauthorizedRole;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultUnauthorizedRole()
    {
        return $this->defaultUnauthorizedRole;
    }
}