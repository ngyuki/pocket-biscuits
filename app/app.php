<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        App::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');

            $app = AppFactory::createFromContainer($container);

            $middleware = require __DIR__ . '/middleware.php';
            $middleware($app);

            $routes = require __DIR__ . '/routes.php';
            $routes($app);

            $app->addRoutingMiddleware();
            $app->addErrorMiddleware((bool)$settings['debug'], true, true);

            $routeCacheFile = $container->get('routes.cache');
            if (file_exists($routeCacheFile)) {
                $app->getRouteCollector()->setCacheFile($routeCacheFile);
            }

            return $app;
        },

        'routes.cache' => __DIR__ . '/cache/routes.php',
    ]);
};
