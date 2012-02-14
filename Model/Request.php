<?php

namespace Nodrew\Bundle\ExceptionalBundle\Model;

use Symfony\Component\HttpFoundation\Request as HttpRequest,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\HttpKernel\Kernel,
    Nodrew\Bundle\ExceptionalBundle\Exceptional\Client;

/**
 * Service request class.
 *
 * @package		ExceptionalBundle
 * @author		Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class Request
{
    protected $config;
    protected $exception;

    /**
     * Build the exceptional data model.
     *
     * @param Exception $exception
     * @param Nodrew\Bundle\ExceptionalBundle\Model\Config $parameters
     */
    public function __construct(\Exception $exception, Config $config)
    {
        $this->exception = $exception;
        $this->config    = $config;
    }

    /**
     * Turn this object into a string.
     *
     * @see toJson()
     * @return string.
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Turn this object into a json encoded string.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->process());
    }

    /**
     * Output the request headers.
     *
     * @param string $url
     * @return string
     */
    public function outputHeaders()
    {
        $url      = sprintf(Client::URL, $this->config->getApiKey(), Client::PROTOCOL_VERSION, $this->getHash());
        $content  = gzencode($this->toJson(), 1);

        $request  = "POST $url HTTP/1.1\r\n";
        $request .= "Host: ".Client::HOST."\r\n";
        $request .= "Accept: */*\r\n";
        $request .= "User-Agent: ".Client::CLIENT_NAME." ".Client::VERSION."\r\n";
        $request .= "Content-Type: text/json\r\n";
        $request .= "Connection: close\r\n";
        $request .= "Content-Length: ".strlen($content)."\r\n\r\n";
        $request .= $content."\r\n";

        return $request;
    }

    /**
     * Process the request model into its Exceptional parameters.
     *
     * @return array
     */
    public function process()
    {
        return array(
            'request'                 => $this->getRequest(),
            'application_environment' => $this->getEnvironment(),
            'exception'               => $this->getException(),
            'client'                  => $this->getClient(),
            'context'                 => $this->getContext(),
        );
    }

    /**
     * Get the request array.
     *
     * @return array
     */
    public function getRequest()
    {
        $request       = $this->config->getRequest();
        $controller    = '';
        $action        = '';

        if ($sa = $request->attributes->get('_controller')) {
            list($controller, $action) = explode('::', $sa);
        }

        $headers = array();

        foreach ($request->headers->all() as $key => $header) {
            $headers[$key] = implode(',', $header);
        }

        return array(
            'session'        => $request->hasSession() ? $request->getSession()->all() : 'N/A',
            'remote_ip'      => $request->getClientIp(),
            'parameters'     => $this->getFilteredParameters(),
            'action'         => $action,
            'url'            => $request->getUri(),
            'request_method' => $request->getMethod(),
            'controller'     => $controller,
            'headers'        => $headers,
        );
    }

    /**
     * Get the filtered parameters, based on the blacklist.
     *
     * @return array
     */
    public function getFilteredParameters()
    {
        $parameters = array_merge($this->config->getRequest()->request->all(), $this->config->getRequest()->query->all());

        foreach ($this->config->getBlacklist() as $term) {
            $parameters = $this->filter($parameters, $term);
        }

        return $parameters;
    }

    /**
     * Filter the given terms from the parameters list.
     *
     * @param array $parameters
     * @param string $term
     * @return array
     */
    public function filter($parameters, $term)
    {
        foreach ($parameters as $key => $value) {
            if (preg_match('/$term/i', $key)) {
                $parameters[$key] = '[FILTERED]';

            } elseif (is_array($value)) {
                $parameters[$key] = $this->filter($value, $term);
            }
        }

        return $parameters;
    }

    /**
     * Get the environment array.
     *
     * @return array
     */
    public function getEnvironment()
    {
        $environment = $this->config->getRequest()->server->all();

        $vars = array('PHPSELF', 'SCRIPT_NAME', 'SCRIPT_FILENAME', 'PATH_TRANSLATED', 'DOCUMENT_ROOT', 'PHP_SELF', 'argv', 'argc', 'REQUEST_TIME', 'PHP_AUTH_PW');
        foreach ($vars as $var) {
            if (isset($environment[$var])) {
                unset($environment[$var]);
            }
        }

        foreach ($environment as $k => $v) {
            if (substr($k, 0, 5) == 'HTTP_') {
              unset($environment[$k]);
            }
        }

        return array(
            'framework'                  => 'Symfony 2',
            'framework_version'          => Kernel::VERSION,
            'env'                        => $environment,
            'host'                       => php_uname('n'),
            'language'                   => 'PHP',
            'language_version'           => phpversion(),
            'application_root_directory' => $this->config->getRootPath(),
            'environment'                => $this->config->getEnvName(),
        );
    }

    /**
     * Get the exception array.
     *
     * @return array
     */
    public function getException()
    {
        $backtrace = array();
        $trace = $this->exception->getTrace();
        foreach ($this->exception->getTrace() as $line) {
            if (!isset($line['file'])) {
                continue;
            }

            $backtrace[] = $line['file'].':'.$line['line'].' in `'.$line['function'].'`';
        }

        array_unshift($backtrace, $this->exception->getFile().':'.$this->exception->getLine());

        return array(
            'occurred_at'     => date('c'),
            'message'         => $this->exception->getMessage(),
            'backtrace'       => $backtrace,
            'exception_class' => get_class($this->exception),
        );
    }

    /**
     * Get a unique hash for this exception.
     *
     * This helps the system understand if this error is recurring.
     *
     * @return string
     */
    public function getHash()
    {
        return sha1(json_encode($this->exception->getTrace()));
    }

    /**
     * Get the client array.
     *
     * @return array
     */
    public function getClient()
    {
        return array(
            'name'             => Client::CLIENT_NAME,
            'version'          => Client::VERSION,
            'protocol_version' => Client::PROTOCOL_VERSION,
        );
    }

    /**
     * Get the context of the error.
     *
     * @return array
     */
    public function getContext()
    {
        return array('context' => array());
    }

}
