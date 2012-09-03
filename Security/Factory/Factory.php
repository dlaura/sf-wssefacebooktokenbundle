<?php

namespace Onfan\WSSEUserPasswordBundle\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class Factory implements SecurityFactoryInterface
{
	public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
	{
		$providerId = 'security.authentication.provider.wsseuserpassword.'.$id;
		$container
			->setDefinition($providerId, new DefinitionDecorator('wsseuserpassword.security.authentication.provider'))
			->replaceArgument(0, new Reference($userProvider))
			->replaceArgument(1, $config['nonce_dir'])
			->replaceArgument(2, $config['lifetime']);

		$listenerId = 'security.authentication.listener.wsseuserpassword.'.$id;
		$listener = $container->setDefinition($listenerId, new DefinitionDecorator('wsseuserpassword.security.authentication.listener'));

		return array($providerId, $listenerId, $defaultEntryPoint);
	}

	public function getPosition()
	{
		return 'pre_auth';
	}

	public function getKey()
	{
		return 'wsseuserpassword';
	}

	public function addConfiguration(NodeDefinition $node)
	{
		$node->children()
                        ->scalarNode('nonce_dir')->defaultValue(null)->end()
			->scalarNode('lifetime')->defaultValue(300)->end()
			->end();
	}
}