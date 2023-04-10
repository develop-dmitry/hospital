<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User\Interface;

use App\Hospital\Domain\User\DTO\SearchByNameRequestInterface;
use App\Hospital\Domain\User\DTO\SearchByNameResponseInterface;

interface UserClientInterface
{
    /**
     * @param SearchByNameRequestInterface $request
     * @return SearchByNameResponseInterface
     */
    public function searchByName(SearchByNameRequestInterface $request): SearchByNameResponseInterface;
}
