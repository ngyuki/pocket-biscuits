<?php
declare(strict_types=1);

use App\Application\Middleware\CookieMiddleware;
use App\Application\Middleware\CsrfMiddleware;
use App\Infrastructure\OAuth\OAuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->add(CookieMiddleware::class);
    $app->add(CsrfMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));

    $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app): ResponseInterface {
        $container = $app->getContainer();
        $oauth = $container->get(OAuthInterface::class);
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $authorize = $routeParser->fullUrlFor($request->getUri(), 'authorize');
        $oauth->setRedirectUri($authorize);
        return $handler->handle($request);
    });
};
