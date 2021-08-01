<?php
declare(strict_types=1);

use App\Application\Services\VonageApiService;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        \Twig\Environment::class => function (ContainerInterface $container): Environment {
            $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/../src/Application/Views');
            $twig = new Twig\Environment($loader, [
                __DIR__ . '/../var/cache'
            ]);
            $twig->enableDebug();
            return $twig;
        },

        \App\Application\Services\VonageApiService::class => function (ContainerInterface $container) {
            $service = new App\Application\Services\VonageApiService(
                $_ENV['VONAGE_API_KEY'],
                $_ENV['VONAGE_API_SECRET'],
            );
            return $service;
        },
    ]);
};
