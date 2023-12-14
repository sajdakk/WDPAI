<?php

class Author
{
    private string $id;
    private string $name;
    private string $surname;

    public function __construct(string $id, string $name, string $surname)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }
}



