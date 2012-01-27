<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\EventListener;

use Nodrew\Bundle\ExceptionalBundle\EventListener\ExceptionListener;

class ExceptionListenerTest extends \PHPUnit_Framework_TestCase
{
    protected function getClient()
    {
        return $this->getMockBuilder('Nodrew\\Bundle\\ExceptionalBundle\\Exceptional\\Client')
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    public function testListener()
    {
        $client = $this->getClient();
        
        $client
            ->expects($this->once())
            ->method('notifyOnException');
            
    
        $event = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\Event\\GetResponseForExceptionEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('__construct'))
            ->getMock();
        
        $r = new \ReflectionObject($event);
        
        $exception = new \Exception('testing');
        $event->setException($exception);

        $listener = new ExceptionListener($client);
        $listener->onKernelException($event);
    }
}