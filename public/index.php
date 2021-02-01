<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

(function () {
    $container = (require __DIR__ . '/../app/container.php')();
    assert($container instanceof ContainerInterface);

    $app = $container->get(App::class);
    $app->run();
})();
