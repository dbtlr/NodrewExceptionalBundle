<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\Model;

use Nodrew\Bundle\ExceptionalBundle\Model\Request,
    Nodrew\Bundle\ExceptionalBundle\Model\Config,
    Symfony\Component\HttpFoundation\Request as HttpRequest,
    Symfony\Component\DependencyInjection\Container;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected function getConfig()
    {
        $request   = HttpRequest::create('/test');
        $request->headers->replace(array('test' => 'yeah!'));

        $container = new Container();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);

        return new Config('asdf', false, 'test', array(), $container);
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

    public function testModelReturnsContextProperly()
    {
        $request = new Request(new \Exception('testing'), $this->getConfig());

        $return = $request->getContext();
        $expectedKeys = array('context');

        $this->assertEquals($expectedKeys, array_keys($return));
    }
}
