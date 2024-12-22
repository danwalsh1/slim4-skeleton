<?php

namespace App\Application\Domain\Factories;

use App\Application\Domain\Models\User;
use App\Application\Exceptions\ValidationException;

class UserFactory extends BaseModelFactory
{
    protected const REQUIRED_FIELDS = ['username', 'first_name', 'last_name'];

    /**
     * @param array<string, mixed> $data
     * @return User
     * @throws ValidationException
     */
    public static function createFromArray(array $data): User
    {
        self::identifyMissingFields($data);

        return new User($data['id'] ?? 0, $data['username'], $data['first_name'], $data['last_name']);
    }
}
