<?php

namespace SpiffyAuthorize\Provider\Identity;

trait ProviderTrait
{
    /**
     * @var string
     */
    protected $defaultGuestRole = 'guest';

    /**
     * @var string
     */
    protected $defaultRole = 'member';

    /**
     * @param string $defaultRole
     * @return ProviderTrait
     */
    public function setDefaultRole($defaultRole)
    {
        $this->defaultRole = $defaultRole;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultRole()
    {
        return $this->defaultRole;
    }

    /**
     * @param string $defaultGuestRole
     * @return ProviderTrait
     */
    public function setDefaultGuestRole($defaultGuestRole)
    {
        $this->defaultGuestRole = $defaultGuestRole;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultGuestRole()
    {
        return $this->defaultGuestRole;
    }
}
