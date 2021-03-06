<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Helper\Redirector;
use App\Infrastructure\OAuth\OAuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private OAuthInterface $oauth,
        private Redirector $redirector,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accessToken = $request->getCookieParams()['accessToken'] ?? null;
        if ($accessToken !== null && strlen($accessToken)) {
            $this->oauth->setAccessToken($accessToken);
        }
        if ($this->oauth->hasAccessToken() == false) {
            return $this->redirector->urlFor($request, 'login');
        }
        return $handler->handle($request);
    }
}
