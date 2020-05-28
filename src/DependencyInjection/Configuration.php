<?php

declare(strict_types=1);

namespace Knyk\MailboxBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('mailbox');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            if (!method_exists($treeBuilder, 'root')) {
                throw new \Exception('Method TreeBuilder::root not found. You are using not supported version of Symfony framework.');
            }
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('mailbox');
        }

        $rootNode
            ->children()
                ->arrayNode('connections')
                    ->isRequired()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('mailbox')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('username')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('password')
                                ->isRequired()
                            ->end()
                            ->scalarNode('attachments_dir')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('server_encoding')
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
