<?php

declare(strict_types=1);

namespace App\Application\Domain\Services;

use App\Application\Domain\Factories\UserFactory;
use App\Application\Domain\Models\User;
use App\Application\Domain\Repositories\UserRepository;
use App\Application\Exceptions\ValidationException;
use App\Application\Validators\Model\UserValidator;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        $userRows = $this->userRepository->getAll();

        $users = [];

        foreach ($userRows as $userRow) {
            $users[] = UserFactory::createFromArray($userRow);
        }

        return $users;
    }

    /**
     * @param int $id
     * @return User
     */
    public function findById(int $id): User
    {
        if ($id < 1) {
            throw new ValidationException('User ID must be greater than 0');
        }

        $userRow = $this->userRepository->findUserById($id);

        return UserFactory::createFromArray($userRow);
    }

    /**
     * @param User $user
     * @return int
     */
    public function create(User $user): int
    {
        $validator = new UserValidator();
        $validator->validateObject($user);

        if (!$validator->getResult()) {
            throw new ValidationException('Invalid user properties', $validator->getErrors());
        }

        return $this->userRepository->create([
            'username' => $user->getUsername(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
        ]);
    }
}
