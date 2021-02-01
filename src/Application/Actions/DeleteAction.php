<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Infrastructure\OAuth\OAuthInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class DeleteAction
{
    public function __construct(
        private OAuthInterface $oauth,
    ) {}

    public function __invoke(ServerRequest $request, Response $response): ResponseInterface
    {
        $item_id = $request->getParsedBodyParam('item_id');
        $this->oauth->delete($item_id);
        return $response->withJson([]);
    }
}
