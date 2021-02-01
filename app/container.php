<?php
declare(strict_types=1);

use DI\ContainerBuilder;

return function (bool $compile = false) {
    $containerBuilder = new ContainerBuilder();

    $cacheDir = __DIR__ . '/cache';
    $containerClass = 'CompiledContainer';
    $cacheFile = "$cacheDir/$containerClass.php";

    if ($compile) {
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
        $containerBuilder->enableCompilation($cacheDir, $containerClass);
    } else {
        if (file_exists($cacheFile)) {
            $containerBuilder->enableCompilation($cacheDir, $containerClass);
            return $containerBuilder->build();
        }
    }

    $settings = require __DIR__ . '/../app/settings.php';
    $settings($containerBuilder);

    $app = require __DIR__ . '/../app/app.php';
    $app($containerBuilder);

    $dependencies = require __DIR__ . '/../app/dependencies.php';
    $dependencies($containerBuilder);

    return $containerBuilder->build();
};
