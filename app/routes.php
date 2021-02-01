<?php
declare(strict_types=1);

use App\Application\Actions\ArchiveAction;
use App\Application\Actions\AuthorizeAction;
use App\Application\Actions\DeleteAction;
use App\Application\Actions\LatestAction;
use App\Application\Actions\LoginAction;
use App\Application\Actions\LogoutAction;
use App\Application\Actions\RandomAction;
use App\Application\Actions\UnarchiveAction;
use App\Application\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->group('', function (Group $group) {
        $group->redirect('/', '/latest')->setName('top');
        $group->get('/latest', LatestAction::class)->setName('latest');
        $group->get('/random', RandomAction::class)->setName('random');
        $group->post('/archive', ArchiveAction::class)->setName('archive');
        $group->post('/unarchive', UnarchiveAction::class)->setName('unarchive');
        $group->post('/delete', DeleteAction::class)->setName('delete');
    })->add(AuthMiddleware::class);

    $app->group('', function (Group $group) {
        $group->get('/login', LoginAction::class)->setName('login');
        $group->get('/authorize', AuthorizeAction::class)->setName('authorize');
        $group->get('/logout', LogoutAction::class)->setName('logout');
    });
};
