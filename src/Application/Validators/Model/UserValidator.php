<?php

namespace App\Application\Validators\Model;

use App\Application\Domain\Models\User;
use App\Application\Validators\Validator;
use Respect\Validation\Validator as v;

class UserValidator extends Validator
{
    private const USERNAME_MIN_LENGTH = 1;
    private const USERNAME_MAX_LENGTH = 255;
    private const FIRST_NAME_MIN_LENGTH = 1;
    private const FIRST_NAME_MAX_LENGTH = 50;
    private const LAST_NAME_MIN_LENGTH = 1;
    private const LAST_NAME_MAX_LENGTH = 50;

    /**
     * @param mixed $username
     * @return void
     */
    public function validateUsername(mixed $username): void
    {
        $validator = v::stringType()
            ->alnum()
            ->noWhitespace()
            ->length(self::USERNAME_MIN_LENGTH, self::USERNAME_MAX_LENGTH)
            ->setName('Username');

        $this->validateValue($validator, $username);
    }

    /**
     * @param mixed $firstName
     * @return void
     */
    public function validateFirstName(mixed $firstName): void
    {
        $validator = v::stringType()
            ->alpha()
            ->length(self::FIRST_NAME_MIN_LENGTH, self::FIRST_NAME_MAX_LENGTH)
            ->setName('First Name');

        $this->validateValue($validator, $firstName);
    }

    /**
     * @param mixed $lastName
     * @return void
     */
    public function validateLastName(mixed $lastName): void
    {
        $validator = v::stringType()
            ->alpha()
            ->length(self::LAST_NAME_MIN_LENGTH, self::LAST_NAME_MAX_LENGTH)
            ->setName('Last Name');

        $this->validateValue($validator, $lastName);
    }

    /**
     * @param mixed $object
     * @return void
     */
    public function validateObject(mixed $object): void
    {
        if (!($object instanceof User)) {
            $this->fail();
            $this->addError('Object is not an instance of User');
        }

        $this->validateUsername($object->getUsername());
        $this->validateFirstName($object->getFirstName());
        $this->validateLastName($object->getLastName());
    }
}
