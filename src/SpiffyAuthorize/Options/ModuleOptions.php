<?php

namespace SpiffyAuthorize\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $defaultRole = 'member';

    /**
     * @var string
     */
    protected $defaultGuestRole = 'guest';

    /**
     * @var string
     */
    protected $identityProvider = 'SpiffyAuthorize\Provider\Identity\AuthenticationProvider';

    /**
     * @var array
     */
    protected $permissionProviders = [];

    /**
     * @var array
     */
    protected $roleProviders = [];

    /**
     * @var array
     */
    protected $guards = [];

    /**
     * @var string
     */
    protected $viewStrategy = 'SpiffyAuthorize\View\Strategy\UnauthorizedStrategy';

    /**
     * @var string
     */
    protected $viewTemplate = 'error/403';

    /**
     * @param string $viewTemplate
     * @return ModuleOptions
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewTemplate()
    {
        return $this->viewTemplate;
    }

    /**
     * @param string $viewStrategy
     * @return ModuleOptions
     */
    public function setViewStrategy($viewStrategy)
    {
        $this->viewStrategy = $viewStrategy;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewStrategy()
    {
        return $this->viewStrategy;
    }

    /**
     * @param array $permissionProviders
     * @return ModuleOptions
     */
    public function setPermissionProviders($permissionProviders)
    {
        $this->permissionProviders = $permissionProviders;
        return $this;
    }

    /**
     * @return array
     */
    public function getPermissionProviders()
    {
        return $this->permissionProviders;
    }

    /**
     * @param array $roleProviders
     * @return ModuleOptions
     */
    public function setRoleProviders($roleProviders)
    {
        $this->roleProviders = $roleProviders;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoleProviders()
    {
        return $this->roleProviders;
    }

    /**
     * @param string $identityProvider
     * @return ModuleOptions
     */
    public function setIdentityProvider($identityProvider)
    {
        $this->identityProvider = $identityProvider;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityProvider()
    {
        return $this->identityProvider;
    }

    /**
     * @param array $guards
     * @return ModuleOptions
     */
    public function setGuards($guards)
    {
        $this->guards = $guards;
        return $this;
    }

    /**
     * @return array
     */
    public function getGuards()
    {
        return $this->guards;
    }

    /**
     * @param string $defaultRole
     * @return ModuleOptions
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
     * @return ModuleOptions
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