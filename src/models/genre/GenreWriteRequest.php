<?php

class GenreWriteRequest
{
    private string $genre;

    public function __construct( string $genre)
    {
        $this->name = $genre;
    }

    public function getGenre()
    {
        return $this->genre;
    }
}



