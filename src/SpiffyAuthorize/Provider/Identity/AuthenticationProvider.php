<?php

namespace SpiffyAuthorize\Provider\Identity;

use SpiffyAuthorize\Identity\IdentityInterface;
use SpiffyAuthorize\Role\RoleInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Acl;
use Zend\Permissions\Rbac;

/**
 * Identity provider that fetches identity from the standard ZF2 authentication service
 */
class AuthenticationProvider implements IdentityProviderInterface
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var string
     */
    protected $defaultGuestRole = 'guest';

    /**
     * @var string
     */
    protected $defaultRole = 'member';

    /**
     * Constructor
     *
     * @param AuthenticationService $authService
     */
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authService;
    }

    /**
     * @param  string $defaultRole
     * @return void
     */
    public function setDefaultRole($defaultRole)
    {
        $this->defaultRole = $defaultRole;
    }

    /**
     * @return string
     */
    public function getDefaultRole()
    {
        return $this->defaultRole;
    }

    /**
     * @param  string $defaultGuestRole
     * @return void
     */
    public function setDefaultGuestRole($defaultGuestRole)
    {
        $this->defaultGuestRole = $defaultGuestRole;
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
        if (!$this->authService->hasIdentity()) {
            return array($this->getDefaultGuestRole());
        }

        $identity = $this->authService->getIdentity();
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