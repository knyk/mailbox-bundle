<?php

declare(strict_types=1);

namespace spec\Knyk\MailboxBundle\Factory;

use Knyk\MailboxBundle\Factory\MailboxFactoryInterface;
use PhpImap\Mailbox;
use PhpSpec\ObjectBehavior;

class MailboxFactorySpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(
            [
                'testConnection' => [
                    'mailbox' => '{example.com:993/imap/ssl}INBOX',
                    'username' => 'username',
                    'password' => 'password',
                    'attachments_dir' => null,
                    'server_encoding' => null,
                ],
            ]
        );
    }

    public function it_should_implement_mailbox_factory_interface(): void
    {
        $this->shouldBeAnInstanceOf(MailboxFactoryInterface::class);
    }

    public function it_should_create_mailbox_for_given_name(): void
    {
        $mailbox = new Mailbox('{example.com:993/imap/ssl}INBOX', 'username', 'password');

        $this->create('testConnection')->shouldBeLike($mailbox);
    }

    public function it_should_throw_exception_if_connection_for_given_name_does_not_exist(): void
    {
        $this->shouldThrow(
            new \Exception('Mailbox connection "dummy" is not configured.')
        )->during('create', ['dummy']);
    }

    public function it_should_throw_exception_if_attachment_dir_does_not_exist(): void
    {
        $this->beConstructedWith(
            [
                'testConnection' => [
                    'mailbox' => '{example.com:993/imap/ssl}INBOX',
                    'username' => 'username',
                    'password' => 'password',
                    'attachments_dir' => 'dummy',
                    'server_encoding' => null,
                ],
            ]
        );

        $this->shouldThrow(new \Exception('Directory "dummy" does not exist.'))
            ->during('create', ['testConnection']);
    }

    public function it_should_throw_exception_if_attachment_dir_is_not_a_valid_dir(): void
    {
        $filePath = __DIR__.'/MailboxFactorySpec.php';

        $this->beConstructedWith(
            [
                'testConnection' => [
                    'mailbox' => '{example.com:993/imap/ssl}INBOX',
                    'username' => 'username',
                    'password' => 'password',
                    'attachments_dir' => $filePath,
                    'server_encoding' => null,
                ],
            ]
        );

        $this->shouldThrow(new \Exception('File "'.$filePath.'" exists but it is not a directory.'))
            ->during('create', ['testConnection']);
    }
}
