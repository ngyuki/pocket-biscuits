<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
use function DI\env;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => [
            'debug' => env('APP_DEBUG', true),

            'logger' => [
                'name' => 'pocket-biscuits',
                'path' => 'php://stdout',
                'level' => Logger::DEBUG,
            ],

            'cacheDir' => env('APP_CACHE_DIR', __DIR__ . '/../data/cache/'),

            'pocket.consumer_key' => getenv('POCKET_CUSTOMER_KEY'),
        ],
    ]);
};
