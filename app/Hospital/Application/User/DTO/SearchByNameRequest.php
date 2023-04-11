<?php

declare(strict_types=1);

namespace App\Hospital\Application\User\DTO;

use App\Hospital\Domain\User\DTO\SearchByNameRequestInterface;

class SearchByNameRequest implements SearchByNameRequestInterface
{
    public function __construct(
        protected string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
