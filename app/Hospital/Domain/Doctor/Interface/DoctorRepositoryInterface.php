<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Doctor\Interface;

use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;

interface DoctorRepositoryInterface
{
    /**
     * @param int $id
     * @return Doctor
     * @throws DoctorNotFoundException
     */
    public function getDoctorByUserId(int $id): Doctor;
}
