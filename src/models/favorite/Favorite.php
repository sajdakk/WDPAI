<?php

class Favorite
{
    private string $id;
    private string $user_id;
    private string $book_id;

    public function __construct(string $id, string $user_id, string $book_id)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->book_id = $book_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getBookId()
    {
        return $this->book_id;
    }
}



