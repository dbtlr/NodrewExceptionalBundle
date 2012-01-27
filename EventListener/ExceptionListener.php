<?php
namespace Nodrew\Bundle\ExceptionalBundle\EventListener;

use Nodrew\Bundle\ExceptionalBundle\Exceptional\Client,
    Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * The ExceptionalBundle ExceptionListener.
 *
 * Handles exceptions that occur in the code base.
 *
 * @package		ExceptionalBundle
 * @author		Drew Butler <drew@abstracting.me>
 * @copyright	(c) 2012 Drew Butler
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class ExceptionListener
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->client->notifyOnException($event->getException());
    }
}
