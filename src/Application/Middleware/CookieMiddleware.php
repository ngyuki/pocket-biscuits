<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Cookies;

class CookieMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Cookies $cookies
    ) {}

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        foreach ($this->cookies->toHeaders() as $value) {
            $response = $response->withAddedHeader('Set-Cookie', $value);
        }
        return $response;
    }
}
