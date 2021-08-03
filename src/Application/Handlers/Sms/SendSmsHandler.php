<?php

namespace App\Application\Handlers\Sms;

use App\Application\Services\VonageApiService;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Twig\Environment;

class SendSmsHandler implements RequestHandlerInterface
{
   public function __construct(Environment $twig, VonageApiService $vonageApiService)
   {
       $this->service = $vonageApiService;
   }

   public function handle(ServerRequestInterface $request): ResponseInterface
   {
        $this->service->sendSms($request->getParsedBody());
        $response = new Response();

        return $response->withHeader('Location', '/');
   }
}