<?php

namespace SpiffyAuthorize\Provider\Identity;

use SpiffyAuthorize\Identity\IdentityInterface;
use SpiffyAuthorize\Provider\AbstractProvider;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Acl;
use Zend\Permissions\Rbac;

class AuthenticationProvider extends AbstractProvider implements ProviderInterface
{
    use ProviderTrait;

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
     * Gets the roles for the identity.
     *
     * @return array
     */
    public function getIdentityRoles()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return [$this->getDefaultGuestRole()];
        }

        $identity = $this->getAuthService()->getIdentity();
        if ($identity instanceof IdentityInterface && 0 !== count($identity->getRoles())) {
            $roles = $identity->getRoles();
            foreach ($roles as $key => $role) {
                if ($role instanceof Acl\Role\RoleInterface) {
                    $roles[$key] = $role->getRoleId();
                } elseif ($role instanceof Rbac\RoleInterface) {
                    $roles[$key] = $role->getName();
                } else if (is_object($role)) {
                    $roles[$key] = (string) $role;
                }
            }
            return $roles;
        }

        return [$this->getDefaultRole()];
    }
}