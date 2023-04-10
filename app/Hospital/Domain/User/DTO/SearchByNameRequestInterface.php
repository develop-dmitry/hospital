<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User\DTO;

interface SearchByNameRequestInterface
{
    public function getName(): string;
}
