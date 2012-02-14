<?php
namespace Nodrew\Bundle\ExceptionalBundle\Exceptional;

use Nodrew\Bundle\ExceptionalBundle\Model\Config,
    Nodrew\Bundle\ExceptionalBundle\Model\Request;

/**
 * The ExceptionalBundle Client Loader.
 *
 * This class assists in the loading of the exceptional-php library.
 *
 * @package		ExceptionalBundle
 * @author		Drew Butler <drew@abstracting.me>
 * @copyright	(c) 2011 Drew Butler
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class Client
{
    const HOST             = 'plugin.getexceptional.com';
    const PROTOCOL_VERSION = '6';
    const URL              = '/api/errors?api_key=%s&protocol_version=%s&hash=%s';
    const VERSION          = '0.6';
    const CLIENT_NAME      = 'NodrewExceptionalBundle';

    protected $config;

    /**
     * @param Nodrew\Bundle\ExceptionalBundle\Model\Config $service
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Exception $exception
     */
    public function notifyOnException(\Exception $exception)
    {
        $request = new Request($exception, $this->config);

        $host    = self::HOST;
        $port    = 80;
        $timeout = 2;
        $errno   = null;
        $errstr  = null;

        if ($this->config->getUseSsl()) {
            $host    = 'ssl://'.self::HOST;
            $port    = 443;
            $timeout = 3;
        }

        if (!$sock = fsockopen($host, $port, $errno, $errstr, $timeout)) {
            return;
        }

        fwrite($sock, $request->outputHeaders());

        $response = "";
        while (!feof($sock)) {
            $response .= fgets($sock);
        }

        fclose($sock);
    }
}
