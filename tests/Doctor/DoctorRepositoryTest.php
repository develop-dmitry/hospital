<?php

declare(strict_types=1);

namespace Tests\Doctor;

use App\Hospital\Application\User\UserBuilder;
use App\Hospital\Domain\Doctor\DoctorBuilder;
use App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface;
use App\Hospital\Domain\User\UserBuilderInterface;
use App\Hospital\Infrastructure\Repository\DoctorRepository;
use App\Hospital\Infrastructure\Repository\UserRepository;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class DoctorRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected DoctorBuilderInterface $doctorBuilder;

    protected UserBuilderInterface $userBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctorBuilder = new DoctorBuilder();
        $this->userBuilder = new UserBuilder();
    }

    /*public function testSaveDoctor()
    {
        $userRepository = new UserRepository($this->userBuilder);
        $doctorRepository = new DoctorRepository($this->doctorBuilder, $userRepository);
        $user = $this->userBuilder
            ->setLogin('dmitry123123')
            ->setName('dmitry')
            ->setEmail('dmitry123123@email.com')
            ->setPassword('12345678')
            ->make();
        $userId = $userRepository->saveUser($user);
        $doctor = $this->doctorBuilder
            ->setUserId($userId)
    }*/
}
