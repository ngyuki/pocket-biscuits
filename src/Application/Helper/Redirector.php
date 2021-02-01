<?php
declare(strict_types=1);

namespace App\Application\Helper;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class Redirector
{
    public function __construct(private ResponseFactoryInterface $responseFactory) {}

    /**
     * {@inheritdoc}
     */
    public function urlFor(Request $request, string $routeName, array $data = [], array $queryParams = []): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $location = $routeParser->fullUrlFor($request->getUri(), $routeName, $data, $queryParams);
        return $this->responseFactory->createResponse(303)->withHeader('location', $location);
    }
}
