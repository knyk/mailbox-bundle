# PHP-IMAP integration bundle

Simple [php-imap](https://github.com/barbushin/php-imap) integration for Symfony 3.x, 4.x and 5.x.

## Installation

#### 1. Composer
From the command line run

```
$ composer require knyk/mailbox-bundle
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
If you're using Symfony Flex open the `config/packages/knyk_mailbox.yaml` and adjust its content.

Here is the example configuration:

```yaml
mailbox:
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
#### Connections
All connections from your configuration will be accessible by injecting service like that:
```yaml
App\YourService:
  arguments:
    $mailbox: '@knyk.mailbox.connection.example_connection'
```


#### With autowiring
In your controller:

```php
<?php

namespace App\Controller;

use Knyk\MailboxBundle\Factory\MailboxFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function indexAction(MailboxFactory $mailboxFactory)
    {
        $exampleConnection = $mailboxFactory->create('example_connection');
        $anotherConnection = $mailboxFactory->create('another_connection');

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
        $exampleConnection = $this->get('Knyk\MailboxBundle\Factory\MailboxFactory')->create('example_connection');
        $anotherConnection = $this->get('Knyk\MailboxBundle\Factory\MailboxFactory')->create('another_connection');

        ...
    }

    ...
}

```

From this point you can use any of the methods provided by the [php-imap](https://github.com/barbushin/php-imap) library. For example


```php
$exampleConnection = $this->get('Knyk\MailboxBundle\Factory\MailboxFactory')->create('example_connection');
$exampleConnection->getMailboxInfo();
```

## Testing

Bundle can be tested by runing PHPUnit and phpspec tests.

`php vendor/bin/phpspec run` - to run phpspec

`php vendor/bin/phpunit tests/` - to run phpunit
