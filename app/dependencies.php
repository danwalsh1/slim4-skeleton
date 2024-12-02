<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\SocketHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder)
{
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $container)
        {
            /** @var SettingsInterface $settings */
            $settings = $container->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');

            $logger = new Logger($loggerSettings['name']);
            $log_file = __DIR__ . '/../logs/monolog.log';

            $logger->pushHandler(new StreamHandler($log_file, $loggerSettings['debug'] ? Level::Debug : Level::Info));

            if(!empty($loggerSettings['papertrail_target']))
            {
                $tcp_handler = new SocketHandler('tls://' . $loggerSettings['papertrail_target'], $loggerSettings['debug'] ? Level::Debug : Level::Info);
                $tcp_handler->setPersistent(true);

                $logger->pushHandler($tcp_handler);
            }

            return $logger;
        }
    ]);
};