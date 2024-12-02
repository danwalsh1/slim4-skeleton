<?php

declare(strict_types=1);

use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (App $app)
{
    $app->group('/api', function (RouteCollectorProxyInterface $group)
    {
        $group->get('/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
        {
            $response->getBody()->write((string)json_encode(['hello' => 'world']));
            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->group('/users', function (RouteCollectorProxyInterface $group)
        {
            $group->get('', ListUsersAction::class);
            $group->post('', CreateUserAction::class);
            $group->get('/{id}', ViewUserAction::class);
        });
    });
};