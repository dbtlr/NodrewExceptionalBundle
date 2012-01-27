<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\DependencyInjection;

use Nodrew\Bundle\ExceptionalBundle\DependencyInjection\NodrewExceptionalExtension;

class NodrewExceptionalExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Nodrew\Bundle\ExceptionalBundle\DependencyInjection\NodrewExceptionalExtension::load
     */
    public function testLoadFailure()
    {
        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $extension = $this->getMockBuilder('Nodrew\Bundle\ExceptionalBundle\DependencyInjection\NodrewExceptionalExtension')
            ->getMock();

        $extension->load(array(array()), $container);
    }

    /**
     * @covers Nodrew\Bundle\ExceptionalBundle\DependencyInjection\NodrewExceptionalExtension:load
     */
    public function testWillLoadWithOnlyKey()
    {
        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $parameterBag = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ParameterBag\\ParameterBag')
            ->disableOriginalConstructor()
            ->getMock();

        $parameterBag
            ->expects($this->any())
            ->method('add');

        $container
            ->expects($this->any())
            ->method('getParameterBag')
            ->will($this->returnValue($parameterBag));

        $configs = array(
            array('api_key' => 'asdasd'),
        );

        $extension = new NodrewExceptionalExtension();
        $extension->load($configs, $container);
    }

    /**
     * @covers Nodrew\Bundle\ExceptionalBundle\DependencyInjection\NodrewExceptionalExtension:load
     */
    public function testWillExplodeWithoutKey()
    {
        $this->setExpectedException('Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException');

        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $parameterBag = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ParameterBag\\ParameterBag')
            ->disableOriginalConstructor()
            ->getMock();
        
        
        $configs = array();
        $extension = new NodrewExceptionalExtension();
        $extension->load($configs, $container);
    }    
}
