<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Infrastructure\OAuth\OAuthInterface;
use App\Infrastructure\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

class LogoutAction
{
    public function __construct(
        private OAuthInterface $oauth,
        private SessionInterface $session,
    ) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->session->clear();
        $location = RouteContext::fromRequest($request)->getRouteParser()->fullUrlFor($request->getUri(), 'top');
        return $response->withStatus(303)->withHeader('location', $location);
    }
}
