<?php

namespace SpiffyAuthorize\Provider\Identity;

use SpiffyAuthorize\Identity\IdentityInterface;
use SpiffyAuthorize\Role\RoleInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Acl;
use Zend\Permissions\Rbac;

class AuthenticationProvider implements ProviderInterface
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
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @param AuthenticationService $authService
     * @return AuthenticationProvider
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        if (!$this->authService) {
            $this->authService = new AuthenticationService();
        }
        return $this->authService;
    }

    /**
     * @param string $defaultRole
     * @return AuthenticationProvider
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
     * @return AuthenticationProvider
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

    /**
     * Gets the roles for the identity.
     *
     * @return array
     */
    public function getIdentityRoles()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return array($this->getDefaultGuestRole());
        }

        $identity = $this->getAuthService()->getIdentity();
        $roles    = array();
        if ($identity instanceof IdentityInterface && 0 !== count($identity->getRoles())) {
            $identityRoles = $identity->getRoles();
            foreach ($identityRoles as $key => $role) {
                if ($role instanceof Acl\Role\RoleInterface) {
                    $roles[$key] = $role->getRoleId();
                } elseif ($role instanceof Rbac\RoleInterface) {
                    $roles[$key] = $role->getName();
                } elseif ($role instanceof RoleInterface) {
                    $roles[$key] = $role->getName();
                } elseif (is_object($role)) {
                    $roles[$key] = (string) $role;
                } else {
                    $roles[$key] = $role;
                }
            }
            return $roles;
        }

        return array($this->getDefaultRole());
    }
}
