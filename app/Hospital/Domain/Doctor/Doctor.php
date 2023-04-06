<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Doctor;

class Doctor
{
    public function __construct(
        protected int $id,
        protected int $userId,
        protected int $departmentId,
        protected string $name,
        protected string $login,
        protected string $email
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Doctor
     */
    public function setId(int $id): Doctor
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Doctor
     */
    public function setUserId(int $userId): Doctor
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartmentId(): int
    {
        return $this->departmentId;
    }

    /**
     * @param int $departmentId
     * @return Doctor
     */
    public function setDepartmentId(int $departmentId): Doctor
    {
        $this->departmentId = $departmentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Doctor
     */
    public function setName(string $name): Doctor
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return Doctor
     */
    public function setLogin(string $login): Doctor
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Doctor
     */
    public function setEmail(string $email): Doctor
    {
        $this->email = $email;
        return $this;
    }
}
