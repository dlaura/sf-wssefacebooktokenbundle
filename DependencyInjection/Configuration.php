<?php

namespace Onfan\WSSEAccessTokenBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('onfan_wsseaccesstoken_authentication');

        $rootNode->children()
                ->scalarNode('provider_class')->defaultValue('Onfan\WSSEAccessTokenBundle\Security\Authentication\Provider\Provider')->end()
        	->scalarNode('listener_class')->defaultValue('Onfan\WSSEAccessTokenBundle\Security\Firewall\Listener')->end()
        	->scalarNode('factory_class')->defaultValue('Onfan\WSSEAccessTokenBundle\Security\Factory\Factory')->end()
        	->end();

        return $treeBuilder;
    }
}