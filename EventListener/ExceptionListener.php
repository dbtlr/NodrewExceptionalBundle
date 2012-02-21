<?php
namespace Nodrew\Bundle\ExceptionalBundle\EventListener;

use Nodrew\Bundle\ExceptionalBundle\Exceptional\Client,
    Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent,
    Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The ExceptionalBundle ExceptionListener.
 *
 * Handles exceptions that occur in the code base.
 *
 * @package		ExceptionalBundle
 * @author		Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class ExceptionListener
{
    protected $client;

    /**
     * Add the client to the listener
     *
     * @param Nodrew\Bundle\ExceptionalBundle\Exceptional\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * This event is called by the kernel when an exception is raised. It will 
     * decide whether exceptional should be notified, based on the exception 
     * type that it has.
     *
     * @param Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        
        if ($exception instanceof HttpException) {
            return;
        }
        
        $this->client->notifyOnException($exception);
    }
}
