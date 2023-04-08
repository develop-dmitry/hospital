<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User\DTO;

interface SearchByNameResponseInterface
{
    public function getUsers(): array;
}
