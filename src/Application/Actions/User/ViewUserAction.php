<?php

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface;

class ViewUserAction extends UserAction
{
    /**
     * @inheritDoc
     */
    #[\Override]
    protected function action(): ResponseInterface
    {
        $userId = (int)$this->resolveArg('id');

        $user = $this->userService->findById($userId);

        $this->logger->info("User of id `{$userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
