<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Hospital\Domain\User\Interface\UserAuthorizationInterface;
use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $userAuthorization = app()->get(UserAuthorizationInterface::class);

        if ($userAuthorization->isAuth()) {
            return $next($request);
        }

        return redirect(route('login'));
    }
}
