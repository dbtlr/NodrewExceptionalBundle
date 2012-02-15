<?php

namespace Nodrew\Bundle\ExceptionalBundle\Model;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Nodrew\Bundle\ExceptionalBundle\Handler\ContextHandlerInterface;

/**
 * Service configuration class.
 *
 * @package		ExceptionalBundle
 * @author		Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class Config
{
    protected $apiKey;
    protected $blacklist;
    protected $request;
    protected $rootPath;
    protected $envName;
    protected $context;
    protected $useSsl = false;

    /**
     * @param string $apiKey
     * @param bool $useSsl
     * @param string $envName
     * @param array $blacklist
     * @param string $contextId
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct($apiKey, $useSsl, $envName, array $blacklist, $contextId, ContainerInterface $container)
    {
        $this->useSsl    = $useSsl;
        $this->apiKey    = $apiKey;
        $this->envName   = $envName;
        $this->blacklist = $blacklist;
        $this->request   = $container->get('request');
        $this->rootPath  = realpath($container->getParameter('kernel.root_dir').'/..');
        
        if (!empty($contextId)) {
            if (!$container->has($contextId)) {
                throw new \RuntimeException('Cannot load ExceptionalBundle, as the given context_id does not point to a valid service id.');
            }
            
            $this->context = $container->get($contextId);
            
            if (!$this->context instanceof ContextHandlerInterface) {
                throw new \LogicException('Cannot load the ExceptionalBundle, because the context handler does not extend from the Nodrew\\Bundle\\ExceptionalBundle\\Handler\\ContextHandlerInterface as required.');
            }
        }
    }
    
    /**
     * @return Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * @return string
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }
    
    /**
     * @return string
     */
    public function getEnvName()
    {
        return $this->envName;
    }
    
    /**
     * @return array
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getUseSsl()
    {
        return $this->useSsl;
    }
}
