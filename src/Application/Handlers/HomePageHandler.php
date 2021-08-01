<?php

namespace App\Application\Handlers;

use App\Application\Services\VonageApiService;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Twig\Environment;

class HomePageHandler implements RequestHandlerInterface
{
   private Environment $twig;

   public function __construct(Environment $twig, VonageApiService $vonageApiService)
   {
       $this->twig = $twig;
       $this->service = $vonageApiService;
   }

   public function handle(ServerRequestInterface $request): ResponseInterface
   {
       $response = new Response();

       $response->getBody()->write(
           $this->twig->render('home.twig', [
               'key' => $this->service->apiKey,
               'secret' => $this->service->apiSecret,
            ])
       );

       return $response;
   }
}