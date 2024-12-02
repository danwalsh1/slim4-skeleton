<?php

declare(strict_types=1);

use App\Application\Domain\Repositories\UserRepository;
use App\Application\Infrastructure\Persistence\UserDatabaseRepository;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => autowire(UserDatabaseRepository::class)
    ]);
};