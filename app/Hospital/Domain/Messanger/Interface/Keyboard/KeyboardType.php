<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Messanger\Interface\Keyboard;

enum KeyboardType: string
{
    case Inline = 'inline';

    case Reply = 'reply';
}
