<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Hospital\Domain\User\Interface\UserAuthorizationInterface;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Menu extends Component
{
    public array $items = [];

    public array $tagClasses = ['nav'];

    private UserAuthorizationInterface $auth;

    public function __construct(Request $request, array $tagClasses)
    {
        $this->auth = app()->get(UserAuthorizationInterface::class);

        if ($this->auth->isAuth()) {
            if ($request->routeIs('profile*') && $this->auth->getUser()->isDoctor()) {
                $this->items = $this->constructDoctorProfileMenu();
            } else {
                $this->items = $this->constructMainMenu();
            }
        } else {
            $this->items = $this->constructMainMenu();
        }

        $this->tagClasses = array_merge($tagClasses, $this->tagClasses);
    }

    private function constructDoctorProfileMenu(): array
    {
        return [
            route('profile-schedule') => 'График работы',
            route('profile-schedule-choose') => 'Выбрать график работы',
        ];
    }

    private function constructMainMenu(): array
    {
        $menu = [
            route('home') => 'Главная'
        ];

        if ($this->auth->isAuth()) {
            $menu[route('profile')] = 'Личный кабинет';
        } else {
            $menu[route('login')] = 'Авторизация';
        }

        return $menu;
    }

    public function render()
    {
        return view('components.nav');
    }
}
