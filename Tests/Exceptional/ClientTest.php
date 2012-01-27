<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\Exceptional;

use Nodrew\Bundle\ExceptionalBundle\Exceptional\Client,
    Nodrew\Bundle\ExceptionalBundle\Model\ServiceParameters;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testClientInstantiates()
    {
        $model = $this->getMockBuilder('Nodrew\\Bundle\\ExceptionalBundle\\Model\\ServiceParameters')
            ->disableOriginalConstructor()
            ->getMock();

        $client = new Client($model);
    }
}