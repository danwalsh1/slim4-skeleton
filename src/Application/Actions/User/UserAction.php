<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Domain\Repositories\UserRepository;
use App\Application\Domain\Services\UserService;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     */
    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->userService = new UserService($userRepository);
    }
}
