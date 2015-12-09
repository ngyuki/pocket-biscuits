<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Application;

$app = new Application();
$app->init();

if ($app['env'] === 'dev') {
    $app['oauth'] = $app->share(function () use ($app) {
        return new \App\OAuthMock($app->path('authorize'));
    });
    $app->run();
}
