<?php

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface;

class ListUsersAction extends UserAction
{
    /**
     * @inheritDoc
     */
    #[\Override]
    protected function action(): ResponseInterface
    {
        $users = $this->userService->getAll();

        return $this->respondWithData($users);
    }
}
