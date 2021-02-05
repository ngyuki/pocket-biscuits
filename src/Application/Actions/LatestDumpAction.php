<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Infrastructure\OAuth\OAuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class LatestDumpAction
{
    public function __construct(
        private OAuthInterface $oauth,
    ) {}

    public function __invoke(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        $list = $this->oauth->retrieveLatest();
        return $response->withJson($list, 200, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
