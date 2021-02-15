<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Infrastructure\OAuth\OAuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Cookies;
use Slim\Routing\RouteContext;

class AuthorizeAction
{
    public function __construct(
        private OAuthInterface $oauth,
        private Cookies $cookies,
    ) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $requestToken = $request->getCookieParams()['requestToken'];
        $accessToken = $this->oauth->fetchAccessToken($requestToken);
        $this->cookies->set('accessToken', ['value' => $accessToken]);
        $this->cookies->set('requestToken', ['expires' => -1]);

        $location = RouteContext::fromRequest($request)->getRouteParser()->fullUrlFor($request->getUri(), 'top');
        return $response->withStatus(303)->withHeader('location', $location);
    }
}
