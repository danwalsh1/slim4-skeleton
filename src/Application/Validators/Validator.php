<?php

namespace App\Application\Validators;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

abstract class Validator
{
    /**
     * @var bool
     */
    private bool $passes = true;

    /**
     * @var string[]
     */
    private array $errors = [];

    /**
     * @param mixed $object
     * @return void
     */
    abstract protected function validateObject(mixed $object): void;

    /**
     * @param Validatable|\Respect\Validation\Validator $validator
     * @param mixed $value
     * @return void
     */
    protected function validateValue(Validatable|\Respect\Validation\Validator $validator, mixed $value): void
    {
        try {
            $validator->assert($value);
        } catch (ValidationException $e) {
            $this->fail();

            if ($e instanceof NestedValidationException) {
                $this->errors = array_merge($this->errors, $e->getMessages());
            } else {
                $this->errors[] = $e->getMessage();
            }
        }
    }

    /**
     * @return void
     */
    protected function fail(): void
    {
        $this->passes = false;
    }

    /**
     * @param string $error
     * @return void
     */
    protected function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return bool
     */
    public function getResult(): bool
    {
        return $this->passes;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
