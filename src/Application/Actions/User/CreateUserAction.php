<?php

namespace App\Application\Actions\User;

use App\Application\Domain\Factories\UserFactory;
use Exception;
use Psr\Http\Message\ResponseInterface;

class CreateUserAction extends UserAction
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    #[\Override]
    protected function action(): ResponseInterface
    {
        $user = UserFactory::createFromArray($this->getFormDataArray());
        $result = $this->userService->create($user);

        if ($result > 0) {
            $resultData = $this->userService->findById($result);
        } else {
            throw new \Exception('Failed to create user');
        }

        return $this->respondWithData($resultData, 201);
    }
}
