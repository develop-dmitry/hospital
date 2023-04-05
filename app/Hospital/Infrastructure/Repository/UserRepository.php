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
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected UserBuilderInterface $userBuilder,
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

    public function findByToken(string $token): User
    {
        try {
            $user = UserModel::where('auth_token', $token)->firstOrFail();

            return $this->userBuilder->makeFromModel($user);
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException("User with token $token not found");
        }
    }

    public function saveUser(User $user): int
    {
        $userModel = UserModel::find($user->getId());

        if (!$userModel) {
            $userModel = new UserModel();
        }

        $userModel->fill([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'login' => $user->getLogin(),
            'password' => ($userModel->exists) ? $user->getPassword() : Hash::make($user->getPassword()),
            'auth_token' => $user->getAuthToken()
        ]);

        if (!$userModel->save()) {
            throw new UserSaveFailedException("Failed to save user");
        }

        return $userModel->id;
    }

    public function changePassword(int $id, string $password): void
    {
        try {
            $userModel = UserModel::findOrFail($id);

            $userModel->password = Hash::make($password);

            if (!$userModel->save()) {
                throw new UserSaveFailedException("Failed to save user with id {$userModel->id}");
            }
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException("User with id $id not found");
        }
    }

    public function dropUser(int $id): void
    {
        if (!UserModel::whereId($id)->delete()) {
            throw new UserDropFailedException("Failed to drop user with id $id");
        }
    }

    public function findById(int $id): User
    {
        try {
            $userModel = UserModel::findOrFail($id);

            return $this->userBuilder->makeFromModel($userModel);
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException("User with id $id not found");
        }
    }
}
