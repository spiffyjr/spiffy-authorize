<?php

namespace SpiffyAuthorizeTest\Provider\Role\Config;

use SpiffyAuthorize\Provider\Role\Config\RbacProvider;
use SpiffyAuthorize\Service\RbacService;

class RbacConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testParentsContainingChildren()
    {
        $provider = new RbacProvider([
            'parent1' => [
                'child1',
                'child2' => [
                    'subchild1'
                ]
            ],
            'parent2' => [
                'child2'
            ],
            'parent2'
        ]);

        $service = new RbacService();
        $service->getEventManager()->attach($provider);

        $rbac = $service->getContainer();
        $this->assertCount(2, $rbac);
        $this->assertInstanceOf('Zend\Permissions\Rbac\RoleInterface', $rbac->getRole('parent1'));
        $this->assertInstanceOf('Zend\Permissions\Rbac\RoleInterface', $rbac->getRole('parent2'));

        $this->assertCount(2, $rbac->getRole('parent1'));
        $this->assertCount(0, $rbac->getRole('subchild1'));

        $this->assertCount(1, $rbac->getRole('parent2'));
    }

    public function testParentsOnly()
    {
        $provider = new RbacProvider([
            'parent1' => [],
            'parent2',
            'parent2'
        ]);

        $service = new RbacService();
        $service->getEventManager()->attach($provider);

        $rbac = $service->getContainer();
        $this->assertCount(2, $rbac);
        $this->assertInstanceOf('Zend\Permissions\Rbac\RoleInterface', $rbac->getRole('parent1'));
        $this->assertInstanceOf('Zend\Permissions\Rbac\RoleInterface', $rbac->getRole('parent2'));
    }

    public function testEmptyConfig()
    {
        $provider = new RbacProvider([]);

        $service = new RbacService();
        $service->getEventManager()->attach($provider);

        $this->assertFalse($service->getContainer()->hasChildren());
    }
}