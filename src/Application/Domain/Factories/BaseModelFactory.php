<?php

namespace App\Application\Domain\Factories;

use App\Application\Exceptions\ValidationException;

use function array_diff;
use function array_keys;
use function implode;

abstract class BaseModelFactory
{
    protected const REQUIRED_FIELDS = [];

    /**
     * @param array<string, mixed> $data
     * @return bool
     * @throws ValidationException
     */
    public static function identifyMissingFields(array $data): bool
    {
        $missingFields = array_diff(self::REQUIRED_FIELDS, array_keys($data));

        if (!empty($missingFields)) {
            throw new ValidationException('Missing required fields: ' . implode(', ', $missingFields));
        }

        return true;
    }

    /**
     * @param array<string, mixed> $data
     * @return mixed
     */
    abstract public static function createFromArray(array $data);
}
