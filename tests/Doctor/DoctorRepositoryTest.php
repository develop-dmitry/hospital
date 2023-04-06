<?php

declare(strict_types=1);

namespace Tests\Doctor;

use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface;
use App\Hospital\Domain\User\Interface\UserBuilderInterface;
use App\Hospital\Infrastructure\Repository\DoctorRepository;
use App\Hospital\Infrastructure\Repository\UserRepository;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class DoctorRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected DoctorBuilderInterface $doctorBuilder;

    protected UserBuilderInterface $userBuilder;

    protected int $departmentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctorBuilder = $this->app->make(DoctorBuilderInterface::class);
        $this->userBuilder = $this->app->make(UserBuilderInterface::class);
        $departmentBuilder = $this->app->make(DepartmentBuilderInterface::class);
        $departmentRepository = $this->app->make(DepartmentRepositoryInterface::class);
        $department = $departmentBuilder
            ->setName('test')
            ->make();

        $this->departmentId = $departmentRepository->saveDepartment($department);
    }

    public function testCreateDoctor()
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
            ->setDepartmentId($this->departmentId)
            ->make();

        $this->expectNotToPerformAssertions();
        $doctorRepository->saveDoctor($doctor);
    }

    public function testGetDoctorById()
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
            ->setDepartmentId($this->departmentId)
            ->make();
        $doctorRepository->saveDoctor($doctor);

        $this->expectNotToPerformAssertions();
        $doctorRepository->getDoctorByUserId($userId);
    }

    public function testGetDoctorByIdFail()
    {
        $userRepository = new UserRepository($this->userBuilder);
        $doctorRepository = new DoctorRepository($this->doctorBuilder, $userRepository);

        $this->expectException(DoctorNotFoundException::class);
        $doctorRepository->getDoctorByUserId(999999);
    }

    public function testSaveDoctor()
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
            ->setDepartmentId($this->departmentId)
            ->make();
        $doctorId = $doctorRepository->saveDoctor($doctor);

        $doctor
            ->setId($doctorId)
            ->setName('ivan');
        $doctorRepository->saveDoctor($doctor);

        $updatedDoctor = $doctorRepository->getDoctorByUserId($userId);
        $this->assertEquals('ivan', $updatedDoctor->getName());
    }
}
