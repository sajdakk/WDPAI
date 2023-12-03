<?php

class Author
{
    private string $name;
    private string $surname;

    public function __construct(string $name, string $surname)
    {
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



