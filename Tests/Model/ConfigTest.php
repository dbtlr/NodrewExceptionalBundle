<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\Model;

use Nodrew\Bundle\ExceptionalBundle\Model\Config,
    Symfony\Component\HttpFoundation\Request,
    Nodrew\Bundle\ExceptionalBundle\Handler\ContextHandlerInterface,
    Symfony\Component\DependencyInjection\Container;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testModelInstatiatesProperly()
    {
        $container = new Container();
        $request   = new Request();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);

        $model = new Config('asdf', false, 'test', array(), null, $container);
    }
    
    public function testWillLoadContextFromIdIfGiven()
    {
        
        $container = new Container();
        $request   = new Request();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);
        $container->set('context.id', new ConfigTestContextTestClass);
        
        $model = new Config('asdf', false, 'test', array(), 'context.id', $container);
        
        $this->assertInstanceOf('Nodrew\\Bundle\\ExceptionalBundle\\Tests\\Model\\ConfigTestContextTestClass', $model->getContext());
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testWillBlowUpIfContextNotInContainer()
    {
        $container = new Container();
        $request   = new Request();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);
        
        $model = new Config('asdf', false, 'test', array(), 'context.id', $container);
    }
    
    /**
     * @expectedException LogicException
     */
    public function testWillBlowUpIfContextNotDescendedFromContextHandlerInterface()
    {
        $container = new Container();
        $request   = new Request();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);
        $container->set('context.id', new \stdClass);
        
        $model = new Config('asdf', false, 'test', array(), 'context.id', $container);
    }
}

class ConfigTestContextTestClass implements ContextHandlerInterface
{
    public $context;
    public function getContext() { return $this->context; }
}