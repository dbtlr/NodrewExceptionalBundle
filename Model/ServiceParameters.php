<?php

namespace Nodrew\Bundle\ExceptionalBundle\Model;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceParameters
{
    protected $apiKey;
    protected $blacklist;
    protected $request;
    protected $rootPath;

    /**
     * @param string $apiKey
     * @param array $blacklist
     * @param string $envName
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct($apiKey, array $blacklist, $envName, ContainerInterface $container)
    {
        $this->apiKey    = $apiKey;
        $this->blacklist = $blacklist;
        $this->request   = $container->get('request');
        $this->rootPath  = realpath($container->getParameter('kernel.root_dir').'/..');
    }

    /**
     * Convert the  model into JSON
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode(array());
    }
    
    /**
     * @return array
     */
    public function getOptions()
    {
        $controller      = 'None';
        $action          = 'None';

        if ($sa = $this->request->attributes->get('_controller')) {
            list($controller, $action) = explode('::', $sa);
        }

        return array(
            'environmentName' => $this->envName,
            'serverData'      => $this->request->server->all(),
            'getData'         => $this->request->query->all(),
            'postData'        => $this->request->request->all(),
            'sessionData'     => $this->request->getSession()->all(),
            'component'       => $controller,
            'action'          => $action,
            'projectRoot'     => $this->rootPath,
        );
    }    
}
