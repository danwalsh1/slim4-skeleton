<?php

declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionError implements JsonSerializable
{
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR = 'SERVER_ERROR';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const VERIFICATION_ERROR = 'VERIFICATION_ERROR';

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string|null
     */
    private ?string $description;

    /**
     * @var string[]|null
     */
    private ?array $errors;

    /**
     * @param string $type
     * @param string|null $description
     * @param string[]|null $errors
     */
    public function __construct(string $type, ?string $description = null, ?array $errors = null)
    {
        $this->type = $type;
        $this->description = $description;
        $this->errors = $errors;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string[]|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param string[]|null $errors
     */
    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @inheritDoc
     * @return array<string, mixed>
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $output = [
            'type' => $this->type,
            'description' => $this->description,
        ];

        if ($this->errors !== null) {
            $output['errors'] = $this->errors;
        }

        return $output;
    }
}
