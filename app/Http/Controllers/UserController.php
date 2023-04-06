<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Hospital\Application\User\UserAuthorization;
use App\Hospital\Domain\User\DTO\AuthorizationRequest;
use App\Hospital\Domain\User\Exception\InvalidUserPasswordException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;
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
        protected UserAuthorization $userAuthorization,
        protected UserRepository $userRepository
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
}
