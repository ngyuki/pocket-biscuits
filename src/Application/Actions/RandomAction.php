<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Infrastructure\OAuth\OAuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class RandomAction
{
    public function __construct(
        private OAuthInterface $oauth,
        private Twig $twig,
    ) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $list = $this->oauth->retrieveRandom();
        $username = $this->oauth->getUsername();

        return $this->twig->render($response, 'index.html.twig', [
            'list' => $list,
            'username' => $username
        ]);
    }
}
