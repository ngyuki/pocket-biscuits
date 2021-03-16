<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Infrastructure\OAuth\OAuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Cookies;

class LoginAction
{
    public function __construct(
        private OAuthInterface $oauth,
        private Cookies $cookies,
    ) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $requestToken = $this->oauth->fetchRequestToken();
        $this->cookies->set('requestToken', ['value' => $requestToken]);
        $authorizeUrl = $this->oauth->generateAuthorizeUrl($requestToken);
        return $response->withStatus(303)->withHeader('location', $authorizeUrl);
    }
}
