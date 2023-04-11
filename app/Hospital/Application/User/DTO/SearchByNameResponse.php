<?php

declare(strict_types=1);

namespace App\Hospital\Application\User\DTO;

use App\Hospital\Domain\User\DTO\SearchByNameResponseInterface;

class SearchByNameResponse implements SearchByNameResponseInterface
{
    public function __construct(
        protected array $users
    ) {
    }

    public function getUsers(): array
    {
        return $this->users;
    }

}
