<?php

namespace App\Application\Handlers;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Twig\Environment;

class HomePageHandler implements RequestHandlerInterface
{
   private Environment $twig;

   public function __construct(Environment $twig)
   {
       $this->twig = $twig;
   }

   public function handle(ServerRequestInterface $request): ResponseInterface
   {
       $response = new Response();

       $response->getBody()->write(
           $this->twig->render('home.twig', ['info' => 'some twig variable'])
       );

       return $response;
   }
}