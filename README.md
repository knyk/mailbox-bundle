# PHP-IMAP integration bundle

Simple [php-imap](https://github.com/barbushin/php-imap) integration for Symfony 3.x, 4.x and 5.x.

## Installation

#### 1. Composer
From the command line run

```
$ composer require knyk/imap-bundle
```

If you're using Symfony Flex you're done and you can go to the configuration section otherwise you must manually register this bundle.

#### 2. Register bundle

If you're not using Symfony Flex you must manually register this bundle in your AppKernel by adding the bundle declaration

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            ...
            new Knyk\MailboxBundle\MailboxBundle(),
        ];

        ...
    }
}
```

## Configuration

Setup your mailbox configuration. If your are using symfony 3.x without Symfony Flex add your configuration in `app/config/config.yml`.
If you're using Symfony Flex open the `config/packages/knyk_imap.yaml` and adjust its content.

Here is the example configuration:

```yaml
knyk_imap:
    connections:
        example_connection:
            mailbox: "{localhost:993/imap/ssl/novalidate-cert}INBOX"
            username: "email@example.com"
            password: "password"

        another_connection:
            mailbox: "{localhost:143}INBOX"
            username: "username"
            password: "password"
            attachments_dir: "%kernel.root_dir%/../var/imap/attachments"
            server_encoding: "UTF-8"
```

## Usage
#### With autowiring
In your controller:

```php
<?php

namespace App\Controller;

use Knyk\MailboxBundle\Factory\MailboxFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function indexAction(MailboxFactory $mailbox)
    {
        $exampleConnection = $mailbox->get('example_connection');
        $anotherConnection = $mailbox->get('another_connection');

        ...
    }

    ...
}

```

#### With service container
In your controller:

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $exampleConnection = $this->get('knyk.imap')->get('example_connection');
        $anotherConnection = $this->get('knyk.imap')->get('another_connection');

        ...
    }

    ...
}

```

From this point you can use any of the methods provided by the [php-imap](https://github.com/barbushin/php-imap) library. For example


```php
$exampleConnection = $this->get('knyk.imap')->get('example_connection');
$exampleConnection->getMailboxInfo();
```
