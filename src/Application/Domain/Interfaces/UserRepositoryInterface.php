<?php

declare(strict_types=1);

namespace App\Application\Domain\Interfaces;

interface UserRepositoryInterface
{
    /**
     * @return array<array<string, mixed>>
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return array<string, mixed>
     */
    public function findUserById(int $id): array;

    /**
     * @param array<string, mixed> $user
     * @return int The new user ID
     */
    public function create(array $user): int;
}
