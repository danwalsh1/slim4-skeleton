<?php

declare(strict_types=1);

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder)
{
    $containerBuilder->addDefinitions([
        PDO::class => function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('RDS_HOSTNAME'), getenv('RDS_DB_NAME'));
            $username = getenv('RDS_USERNAME');
            $password = getenv('RDS_PASSWORD');

            if (!$username || !$password)
            {
                throw new Exception('Database connection details not set.');
            }

            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
    ]);
};