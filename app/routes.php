<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Handlers\HomePageHandler;
use App\Application\Handlers\Sms\SendSmsHandler;
use App\Application\Handlers\VonageApplication\CreateApplicationHandler;
use App\Application\Handlers\VonageApplication\DeleteApplicationHandler;
use App\Application\Handlers\VonageApplication\EditApplicationHandler;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', HomePageHandler::class)->setName('home');

    $app->group('/applications', function (Group $group) {
        $group->post('/create', CreateApplicationHandler::class);
        $group->post('/edit', EditApplicationHandler::class);
        $group->post('/delete', DeleteApplicationHandler::class);
    });

    $app->group('/sms', function (Group $group) {
        $group->post('/send', SendSmsHandler::class);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
