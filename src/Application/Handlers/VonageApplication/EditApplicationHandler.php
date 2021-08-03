<?php

namespace App\Application\Handlers\VonageApplication;

use App\Application\Services\VonageApiService;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class EditApplicationHandler implements RequestHandlerInterface
{
   public function __construct(VonageApiService $vonageApiService)
   {
       $this->service = $vonageApiService;
   }

   public function handle(ServerRequestInterface $request): ResponseInterface
   {
       $this->service->updateVonageApplication($request->getParsedBody());
       $response = new Response();

       return $response->withHeader('Location', '/');
   }
}