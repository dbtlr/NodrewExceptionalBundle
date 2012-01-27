<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\Exceptional;

use Nodrew\Bundle\ExceptionalBundle\Exceptional\Client,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\Container;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testClientInstantiates()
    {
        $container = new Container();
        $request   = new Request();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);

        $client = new Client('asdf', array(), 'test', $container);
    }
}