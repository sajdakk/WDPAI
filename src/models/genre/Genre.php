<?php

class Genre
{
    private string $id;
    private string $title;

    public function __construct(string $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getGenre()
    {
        return $this->title;
    }


    public function getId()
    {
        return $this->id;
    }

}



