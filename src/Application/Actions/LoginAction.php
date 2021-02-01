<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Infrastructure\OAuth\OAuthInterface;
use App\Infrastructure\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginAction
{
    public function __construct(
        private OAuthInterface $oauth,
        private SessionInterface $session,
    ) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $requestToken = $this->oauth->fetchRequestToken();
        $this->session->set('requestToken', $requestToken);
        $authorizeUrl = $this->oauth->generateAuthorizeUrl($requestToken);
        return $response->withStatus(303)->withHeader('location', $authorizeUrl);
    }
}
