<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\User\DTO\SearchByNameRequest;
use App\Hospital\Domain\User\DTO\AuthorizationRequest;
use App\Hospital\Domain\User\Exception\InvalidUserPasswordException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;
use App\Hospital\Domain\User\Interface\UserAuthorizationInterface;
use App\Hospital\Domain\User\Interface\UserClientInterface;
use App\Hospital\Domain\User\UserAuthorization;
use App\Hospital\Infrastructure\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Lumen\Application;
use Laravel\Lumen\Http\Redirector;

class UserController extends Controller
{
    public function __construct(
        protected UserAuthorizationInterface $userAuthorization,
        protected UserClientInterface $userClient
    ) {
    }

    public function login(): Application|RedirectResponse|View|Redirector
    {
        if ($this->userAuthorization->isAuth()) {
            return redirect(route('profile'));
        }

        return view('login');
    }

    public function authorization(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required'
            ]);

            $authorizationRequest = new AuthorizationRequest(
                $request->get('email'),
                $request->get('password')
            );

            $this->userAuthorization->auth($authorizationRequest);
        } catch (ValidationException) {
            return response()->json(['success' => false, 'message' => 'Проверьте заполненность полей']);
        } catch (InvalidUserPasswordException) {
            return response()->json(['success' => false, 'message' => 'Неверный логин или пароль']);
        } catch (UserNotFoundException) {
            return response()->json(['success' => false, 'message' => 'Пользователь с таким email не найден']);
        } catch (UserSaveFailedException) {
            return response()->json(['success' => false, 'message' => 'Произошла ошибка, попробуйте позже']);
        }

        return response()->json(['success' => true]);
    }

    public function searchByName(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'name' => 'required'
            ]);

            $response = $this->userClient->searchByName(new SearchByNameRequest($request->get('name')));

            return response()->json(['success' => true, 'users' => $response->getUsers()]);
        } catch (ValidationException) {
            return response()->json(['success' => false, 'message' => 'Некорректный запрос']);
        }
    }
}
