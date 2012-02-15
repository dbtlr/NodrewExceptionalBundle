<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\Model;

use Nodrew\Bundle\ExceptionalBundle\Model\Request,
    Nodrew\Bundle\ExceptionalBundle\Model\Config,
    Nodrew\Bundle\ExceptionalBundle\Handler\ContextHandlerInterface,
    Symfony\Component\HttpFoundation\Request as HttpRequest,
    Symfony\Component\DependencyInjection\Container;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected function getConfig($contextId = null, $context = null, $blacklist = array())
    {
        $request   = HttpRequest::create('/test');
        $request->headers->replace(array('test' => 'yeah!'));
        $request->request->replace(array('password' => 'password', 'password2' => 'password2', 'name' => 'John Doe'));

        $container = new Container();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);
        
        if ($contextId) {
            $container->set($contextId, $context);
        }

        return new Config('asdf', false, 'test', $blacklist, $contextId, $container);
    }

    public function testModelInstatiatesProperly()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig());
    }

    public function testModelReturnsRequestProperly()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig());

        $return = $request->getRequest();
        $expectedKeys = array('session', 'remote_ip', 'parameters', 'action', 'url', 'request_method', 'controller', 'headers');

        $this->assertEquals($expectedKeys, array_keys($return));
    }

    public function testModelReturnsEnvironmentProperly()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig());

        $return = $request->getEnvironment();
        $expectedKeys = array('framework', 'framework_version', 'env', 'host', 'language', 'language_version', 'application_root_directory', 'environment');

        $this->assertEquals($expectedKeys, array_keys($return));
    }

    public function testModelReturnsExceptionProperly()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig());

        $return = $request->getException();
        $expectedKeys = array('occurred_at', 'message', 'backtrace', 'exception_class');

        $this->assertEquals($expectedKeys, array_keys($return));
    }

    public function testModelReturnsClientProperly()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig());

        $return = $request->getClient();
        $expectedKeys = array('name', 'version', 'protocol_version');

        $this->assertEquals($expectedKeys, array_keys($return));
    }

    public function testModelReturnsContextAsEmptyWhenNoContextClassDefined()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig());

        $this->assertNull($request->getContext());
    }

    public function testWillUseContextIfDefined()
    {
        $context = new ContextTestClass;
        $context->context = array('test' => 'works');
        $request = new Request(new \Exception('testing'), $this->getConfig('context.id', $context));
        
        $this->assertEquals(array('context' => array('test' => 'works')), $request->getContext());
    }

    public function testWillSkipContextIfNonArrayReturned()
    {
        $context = new ContextTestClass;
        $context->context = 'not an array';
        $request = new Request(new \Exception('testing'), $this->getConfig('context.id', $context));
        
        $this->assertNull($request->getContext());
    }
    
    public function testWillFilterOutParametersBasedOnBlackList()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig(null, null, array('name')));
        
        $return = $request->getRequest();
        
        $this->assertSame(array('password' => 'password', 'password2' => 'password2', 'name' => '[PROTECTED]'), $return['parameters']);
    }
    
    public function testWillFilterOutParametersBasedOnBlackListAndWillMatchBasedOnPartialWords()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig(null, null, array('password')));
        
        $return = $request->getRequest();
        
        $this->assertSame(array('password' => '[PROTECTED]', 'password2' => '[PROTECTED]', 'name' => 'John Doe'), $return['parameters']);
    }
}

class ContextTestClass implements ContextHandlerInterface
{
    public $context;
    public function getContext() { return $this->context; }
}