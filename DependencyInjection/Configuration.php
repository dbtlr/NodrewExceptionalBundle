<?php

namespace Nodrew\Bundle\ExceptionalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @package     NodrewExceptionalBundle
 * @author      Drew Butler <hi@nodrew.com>
 * @copyright	(c) 2012 Drew Butler
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return Symfony\Component\Config\Definition\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nodrew_exceptional', 'array');

        $rootNode
            ->children()
                ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('use_ssl')->end()
                ->arrayNode('blacklist')
                    ->useAttributeAsKey('blacklist')->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}
