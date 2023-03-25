<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class Menu extends Component
{
    public array $items;

    public array $tagClasses = ['nav'];

    public function __construct(array $tagClasses)
    {
        $this->items = [
            route('profile-schedule') => 'График работы',
            route('profile-schedule-choose') => 'Выбрать график работы',
            route('profile-analyze') => 'Анализы',
            route('profile-analyze-upload') => 'Загрузить анализы'
        ];

        $this->tagClasses = array_merge($tagClasses, $this->tagClasses);
    }

    public function render()
    {
        return view('components.nav');
    }
}
