<?php

declare(strict_types=1);

use App\Application\Domain\Interfaces\UserRepositoryInterface;
use App\Application\Infrastructure\Persistence\Repositories\UserDatabaseRepository;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder)
{
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => autowire(UserDatabaseRepository::class)
    ]);
};
