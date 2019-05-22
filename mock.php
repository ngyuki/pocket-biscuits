<?php
require __DIR__ . '/vendor/autoload.php';

if (is_file($_SERVER['SCRIPT_FILENAME']) && !preg_match('/\.php\z/', $_SERVER['SCRIPT_FILENAME'])) {
    return false;
}

use App\Application;
use App\OAuthMock;

$app = new Application();
$app->init();

if ($app['env'] === 'dev') {
    $app['oauth'] = $app->share(function () use ($app) {
        return new OAuthMock($app->path('authorize'));
    });
    $app->run();
}
