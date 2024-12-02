<?php

namespace App\Application\Domain\Factories;

use App\Application\Domain\Models\User;
use App\Application\Exceptions\ValidationException;
use DomainException;

class UserFactory
{
    private const REQUIRED_FIELDS = ['username', 'first_name', 'last_name'];

    /**
     * @param array<string, mixed> $data
     * @return User
     */
    public static function createFromArray(array $data): User
    {
        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($data));

        if (!empty($missingFields)) {
            throw new ValidationException('Missing required fields: ' . implode(', ', $missingFields));
        }

        return new User($data['id'] ?? 0, $data['username'], $data['first_name'], $data['last_name']);
    }
}
