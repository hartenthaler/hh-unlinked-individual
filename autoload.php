<?php

declare(strict_types=1);

namespace Hartenthaler\Webtrees\Module\UnlinkedIndividual;

use Composer\Autoload\ClassLoader;

$loader = new ClassLoader();

$loader->addPsr4('Hartenthaler\\Webtrees\\Module\\UnlinkedIndividual\\', __DIR__);

$loader->register();

return true;
