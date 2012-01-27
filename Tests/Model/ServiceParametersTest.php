<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\Model;

use Nodrew\Bundle\ExceptionalBundle\Model\ServiceParameters,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\Container;

class ServiceParametersTest extends \PHPUnit_Framework_TestCase
{
    public function testModelInstatiatesProperly()
    {
        $container = new Container();
        $request   = new Request();
        $container->set('request', $request);
        $container->setParameter('kernel.root_dir', __DIR__);

        $model = new ServiceParameters('asdf', array(), 'test', $container);
    }
}
