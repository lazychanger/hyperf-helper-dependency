# hyperf-helper/dependency
## Badges
[![PHP Composer](https://github.com/lazychanger/hyperf-helper-dependency/actions/workflows/php.yml/badge.svg)](https://github.com/lazychanger/hyperf-helper-dependency/actions/workflows/php.yml)
[![PHPUnit](https://github.com/lazychanger/hyperf-helper-dependency/actions/workflows/test.yml/badge.svg)](https://github.com/lazychanger/hyperf-helper-dependency/actions/workflows/test.yml)
[![Release](https://github.com/lazychanger/hyperf-helper-dependency/actions/workflows/release.yml/badge.svg)](https://github.com/lazychanger/hyperf-helper-dependency/actions/workflows/release.yml)
## Introduction
Easy, simple and elegant way to add dependencies in Hyperf

## Start

```
composer requpre hyperf-helper/dependency
```

## Annotation Params

| name       | type                 | default | comment                                                                                                      |
|------------|----------------------|---------|--------------------------------------------------------------------------------------------------------------|
| identifier | string<class-string> | ''      | Dependency identifier. if empty, default value is the annotation className                                   |
| priority   | int                  | 1       | If there are multiple identical `Dependency` identifiers, the one with the highest priority will be selected |

## How to use

1. **We need to add dependencies collected from `DependencyCollector` to `Container`**

```php
<?php
# config/container.php

<?php
/**
 * Initialize a dependency injection container that implemented PSR-11 and return the container.
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSourceFactory;
use Hyperf\Utils\ApplicationContext;

$container = new Container((new DefinitionSourceFactory(true))());

if (! $container instanceof \Psr\Container\ContainerInterface) {
    throw new RuntimeException('The dependency injection container is invalid.');
}

/*********     start      ********/
// Add this line between `new Container` and `setContainer()`
\HyperfHelper\Dependency\Annotation\Collector\DependencyCollector::walk([$container, 'define']);
/*********      end       ********/

return ApplicationContext::setContainer($container);

```

2. **Use `Dependency` to annotate the dependent class you want to define**

```php

declare(strict_types=1);

namespace App\Service;

use HyperfHelper\Dependency\Annotation\Dependency;

// add Dependency annotation
#[Dependency()]
class ExampleService implements ExampleServiceInterface {
    // anything
}

```

3. **Happy using `Inject` everywhere**

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use App\Service\ExampleServiceInterface;


class FooController {

    #[Inject]
    protected ExampleServiceInterface $service;
}

```
