<?php

declare(strict_types=1);

namespace Knyk\MailboxBundle\Tests\DependencyInjection;

use Knyk\MailboxBundle\DependencyInjection\MailboxExtension;
use Knyk\MailboxBundle\Factory\MailboxFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MailboxExtensionTest extends TestCase
{
    public function testMailboxExtensionShouldRegisterServicesInContainer(): void
    {
        $container = new ContainerBuilder();

        $extension = new MailboxExtension();

        $config = [
            'connections' => [
                'testConnection' => [
                    'mailbox' => '{example.com:993/imap/ssl}INBOX',
                    'username' => 'username',
                    'password' => 'password',
                ],
                'testConnection2' => [
                    'mailbox' => '{example2.com:993/imap/ssl}INBOX',
                    'username' => 'username2',
                    'password' => 'password2',
                ],
            ],
        ];

        $extension->load([$config], $container);

        $this->assertTrue($container->hasParameter('knyk.mailbox.connections'));
        $this->assertEquals($config['connections'], $container->getParameter('knyk.mailbox.connections'));

        $this->assertTrue($container->hasDefinition('Knyk\MailboxBundle\Factory\MailboxFactory'));

        $mailboxFactory = $container->get('Knyk\MailboxBundle\Factory\MailboxFactory');

        $this->assertInstanceOf(MailboxFactory::class, $mailboxFactory);

        $mailboxFactoryDefinition = $container->getDefinition('Knyk\MailboxBundle\Factory\MailboxFactory');

        $this->assertEquals('%knyk.mailbox.connections%', $mailboxFactoryDefinition->getArgument(0));
    }
}
