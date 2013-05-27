<?php

namespace SpiffyAuthorizeTest\Service;

use SpiffyAuthorize\Service\OptionsModuleOptionsFactory;
use Zend\ServiceManager\ServiceManager;

class OptionsModuleOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testModuleOptionsInstanceReturned()
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('Configuration', [
            'spiffy-authorize' => [
                'default_role' => 'foo'
            ]
        ]);

        $factory = new OptionsModuleOptionsFactory();
        $options = $factory->createService($serviceManager);
        $this->assertInstanceOf('SpiffyAuthorize\Options\ModuleOptions', $options);
        $this->assertEquals('foo', $options->getDefaultRole());
    }
}