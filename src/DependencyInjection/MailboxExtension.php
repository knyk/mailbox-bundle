<?php

declare(strict_types=1);

namespace Knyk\MailboxBundle\DependencyInjection;

use PhpImap\Mailbox;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class MailboxExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('knyk.mailbox.connections', $config['connections']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->registerMailboxesForConnections($config['connections'], $container);
    }

    private function registerMailboxesForConnections(array $connections, ContainerBuilder $container): void
    {
        foreach (array_keys($connections) as $name) {
            $definitionName = sprintf('knyk.mailbox.connection.%s', $name);

            $definition = new Definition($definitionName);
            $definition->setFactory([new Reference('Knyk\MailboxBundle\Factory\MailboxFactory'), 'create']);
            $definition->setClass(Mailbox::class);
            $definition->setArgument(0, $name);

            $container->setDefinition($definitionName, $definition);
        }
    }
}
