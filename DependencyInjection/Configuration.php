<?php

namespace Onfan\WSSEUserPasswordBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('onfan_wsseuserpassword_authentication');

        $rootNode->children()
                ->scalarNode('provider_class')->defaultValue('Onfan\WSSEUserPasswordBundle\Security\Authentication\Provider\Provider')->end()
        	->scalarNode('listener_class')->defaultValue('Onfan\WSSEUserPasswordBundle\Security\Firewall\Listener')->end()
        	->scalarNode('factory_class')->defaultValue('Onfan\WSSEUserPasswordBundle\Security\Factory\Factory')->end()
        	->end();

        return $treeBuilder;
    }
}