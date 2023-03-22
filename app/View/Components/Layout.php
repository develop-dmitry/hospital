<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public string $title;

    public function __construct(string $title) {
        $this->title = $title . ' - ' . env('APP_NAME');
    }

    public function render(callable $callback = null)
    {
        return view('components.layout');
    }
}
