<?php

namespace SpiffyAuthorizeTest\Asset;

use SpiffyAuthorize\Provider\Role\ProviderInterface;
use SpiffyAuthorize\AuthorizeEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Permissions\Rbac\Role;

class RbacRoleProvider implements ProviderInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(AuthorizeEvent::EVENT_INIT, array($this, 'load'));
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param AuthorizeEvent $e
     * @return array
     */
    public function load(AuthorizeEvent $e)
    {
        /** @var \Zend\Permissions\Rbac\Rbac $rbac */
        $rbac = $e->getTarget();

        $role1 = new Role('role1');
        $role1->addPermission('role1');
        $role2 = new Role('role2');
        $role2->addPermission('role2');

        $child1 = new Role('child1');
        $child2 = new Role('child2');
        $child2->addPermission('child2');
        $child3 = new Role('child3');
        $child3->addPermission('child3');

        $subchild1 = new Role('subchild1');
        $subchild2 = new Role('subchild2');
        $subchild1->addPermission('subchild1');

        $role1->addChild($child1);
        $role1->addChild($child2);

        $role2->addChild($child3);

        $child1->addChild($subchild1);
        $child1->addChild($subchild2);

        $rbac->addRole($role1);
        $rbac->addRole($role2);
    }
}