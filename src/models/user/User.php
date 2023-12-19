<?php

class User
{
    private $id;
    private $email;
    private $password;
    private $name;
    private $surname;

    private $avatar;
    private $role;

    public function __construct(
        int $id,
        string $email,
        string $password,
        string $name,
        string $surname,
        ?string $avatar,
        string $role
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->avatar = $avatar;
        $this->role= $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }



}