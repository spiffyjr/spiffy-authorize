<?php

namespace SpiffyAuthorizeTest\Factory;

use SpiffyAuthorize\Factory\OptionsModuleFactory;
use Zend\ServiceManager\ServiceManager;

class OptionsModuleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testModuleOptionsInstanceReturned()
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('Configuration', array(
            'spiffy_authorize' => array(
                'default_role' => 'foo'
            )
        ));

        $factory = new OptionsModuleFactory();
        $options = $factory->createService($serviceManager);
        $this->assertInstanceOf('SpiffyAuthorize\ModuleOptions', $options);
        $this->assertEquals('foo', $options->getDefaultRole());
    }
}
