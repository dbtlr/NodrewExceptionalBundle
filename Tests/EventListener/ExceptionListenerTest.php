<?php

namespace Nodrew\Bundle\ExceptionalBundle\Tests\EventListener;

use Nodrew\Bundle\ExceptionalBundle\EventListener\ExceptionListener,
    Symfony\Component\HttpKernel\Exception\HttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        
        $exception = new \Exception('testing');
        $event->setException($exception);

        $listener = new ExceptionListener($client);
        $listener->onKernelException($event);
    }
    
    public function testWillSkipHttpException()
    {
        $client = $this->getClient();
        
        $client
            ->expects($this->never())
            ->method('notifyOnException');
            
        $event = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\Event\\GetResponseForExceptionEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('__construct'))
            ->getMock();
        
        $exception = new HttpException(403);
        $event->setException($exception);

        $listener = new ExceptionListener($client);
        $listener->onKernelException($event);
    }
    
    public function testWillNotSkipHttpExceptionIf404()
    {
        $client = $this->getClient();
        
        $client
            ->expects($this->once())
            ->method('notifyOnException');
            
        $event = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\Event\\GetResponseForExceptionEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('__construct'))
            ->getMock();
        
        $exception = new NotFoundHttpException();
        $event->setException($exception);

        $listener = new ExceptionListener($client);
        $listener->onKernelException($event);
    }
}