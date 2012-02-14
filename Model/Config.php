<?php

namespace Nodrew\Bundle\ExceptionalBundle\Model;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\ContainerInterface;

class Config
{
    protected $apiKey;
    protected $blacklist;
    protected $request;
    protected $rootPath;
    protected $envName;
    protected $useSsl = false;

    /**
     * @param string $apiKey
     * @param array $blacklist
     * @param string $envName
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct($apiKey, $useSsl, $envName, array $blacklist, ContainerInterface $container)
    {
        $this->useSsl    = $useSsl;
        $this->apiKey    = $apiKey;
        $this->envName   = $envName;
        $this->blacklist = $blacklist;
        $this->request   = $container->get('request');
        $this->rootPath  = realpath($container->getParameter('kernel.root_dir').'/..');
    }
    
    /**
     * @return Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
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
