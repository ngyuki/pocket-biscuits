<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$container = (require __DIR__ . '/../app/container.php')(true);
assert($container instanceof ContainerInterface);

$routeCacheFile = $container->get('routes.cache');
if (file_exists($routeCacheFile)) {
    unlink($routeCacheFile);
}
$app = $container->get(App::class);
$app->getRouteCollector()->setCacheFile($routeCacheFile);
$app->getRouteResolver()->computeRoutingResults('/', 'GET');
