<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\User\Exception\UserDropFailedException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;
use App\Hospital\Domain\User\User;
use App\Hospital\Domain\User\UserBuilderInterface;
use App\Hospital\Domain\User\UserRepositoryInterface;
use App\Models\User as UserModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected UserBuilderInterface $userBuilder
    ) {
    }

    public function findByEmail(string $email): User
    {
        try {
            $user = UserModel::where('email', $email)->firstOrFail();

            return $this->userBuilder->makeFromModel($user);
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException("User with email $email not found");
        }
    }

    public function saveUser(User $user): void
    {
        $userModel = new UserModel();

        $userModel->fill([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'auth_token' => $user->getAuthToken()
        ]);

        if (!$userModel->save()) {
            throw new UserSaveFailedException('Failed to save user');
        }
    }

    public function dropUser(int $id): void
    {
        if (!UserModel::whereId($id)->delete()) {
            throw new UserDropFailedException("Failed to drop user with id $id");
        }
    }
}
