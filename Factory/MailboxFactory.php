<?php

declare(strict_types=1);

namespace Knyk\MailboxBundle\Factory;

use PhpImap\Mailbox;

class MailboxFactory implements MailboxFactoryInterface
{
    /**
     * @var array[]
     */
    protected $connections;

    /**
     * @var Mailbox[]
     */
    protected $instances = [];

    public function __construct(array $connections)
    {
        $this->connections = $connections;
    }

    public function create(string $name): Mailbox
    {
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $this->getMailbox($name);
        }

        return $this->instances[$name];
    }

    protected function getMailbox(string $name): Mailbox
    {
        if (!isset($this->connections[$name])) {
            throw new \Exception(sprintf('Mailbox connection %s is not configured.', $name));
        }

        $config = $this->connections[$name];

        if (isset($config['attachments_dir'])) {
            $this->guardAttachmentDirectory($config['attachments_dir']);
        }

        return new Mailbox(
            $config['mailbox'],
            $config['username'],
            $config['password'],
            $config['attachments_dir'] ?? null,
            $config['server_encoding'] ?? 'UTF-8'
        );
    }

    protected function guardAttachmentDirectory(string $directoryPath): void
    {
        if (!file_exists($directoryPath)) {
            throw new \Exception('Directory "%s" does not exist.', $directoryPath);
        }

        if (!is_dir($directoryPath)) {
            throw new \Exception(sprintf('File "%s" exists but it is not a directory', $directoryPath));
        }

        if (!is_readable($directoryPath) || !is_writable($directoryPath)) {
            throw new \Exception(sprintf('Directory "%s" does not have expected access permissions', $directoryPath));
        }
    }
}
