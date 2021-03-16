<?php
declare(strict_types=1);

use App\Infrastructure\OAuth\OAuth;
use App\Infrastructure\OAuth\OAuthInterface;
use App\Infrastructure\OAuth\OAuthMock;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Http\Factory\DecoratedResponseFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Cookies;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Views\Twig;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([

        LoggerInterface::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $loggerSettings = $settings['logger'];

            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        ResponseFactoryInterface::class => function () {
            return new DecoratedResponseFactory(new ResponseFactory(), new StreamFactory());
        },

        RouteParserInterface::class => function (ContainerInterface $container): RouteParserInterface {
            return $container->get(App::class)->getRouteCollector()->getRouteParser();
        },

        Twig::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $options = [
                'debug' => $settings['debug'],
                'strict_variables' => true,
                'cache' => $settings['debug'] ? false : $settings['cacheDir'] . '/twig',
            ];
            return Twig::create(__DIR__ . '/../templates', $options);
        },

        OAuthInterface::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $consumerKey = (string)$settings['pocket.consumer_key'];
            if (strlen($consumerKey)) {
                return new OAuth($consumerKey);
            } else {
                return new OAuthMock();
            }
        },

        Cookies::class => function () {
            $cookies = new Cookies();
            $cookies->setDefaults( [
                'hostonly' => true,
                'path' => '/',
                'expires' => null,
                //'secure' => true,
                'httponly' => true,
                //'samesite' => 'strict',
            ]);
            return $cookies;
        },
    ]);
};
