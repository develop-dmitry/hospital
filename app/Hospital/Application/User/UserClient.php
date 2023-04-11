<?php

declare(strict_types=1);

namespace App\Hospital\Application\User;

use App\Hospital\Application\User\DTO\SearchByNameResponse;
use App\Hospital\Domain\User\DTO\SearchByNameRequestInterface;
use App\Hospital\Domain\User\DTO\SearchByNameResponseInterface;
use App\Hospital\Domain\User\Interface\UserClientInterface;
use App\Hospital\Domain\User\Interface\UserRepositoryInterface;

class UserClient implements UserClientInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function searchByName(SearchByNameRequestInterface $request): SearchByNameResponseInterface
    {
        $result = [];
        $users = $this->userRepository->searchByName($request->getName());

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
            ];
        }

        return new SearchByNameResponse($result);
    }

}
