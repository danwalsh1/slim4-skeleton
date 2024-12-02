<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use RuntimeException;
use Throwable;

class ValidationException extends RuntimeException
{
    /**
     * @var string[]
     */
    private array $errors;

    /**
     * @param string $message
     * @param string[] $errors
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, array $errors = [], int $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
