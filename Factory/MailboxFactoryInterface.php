<?php

declare(strict_types=1);

namespace Knyk\MailboxBundle\Factory;

use PhpImap\Mailbox;

interface MailboxFactoryInterface
{
    public function create(string $name): Mailbox;
}
