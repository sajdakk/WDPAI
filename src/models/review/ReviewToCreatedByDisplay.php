<?php

class ReviewToCreatedByDisplay
{
    private string $id;
    private string $book_id;

    private string $user_name;
    private ?string $user_avatar;
    private string $user_id;
    private string $content;
    private int $rate;
    private string $upload_date;
    private ?string $accept_date;
    private ?string $reject_date;

    public function __construct(
        string $id,
        string $book_id,
        string $user_name,
        ?string $user_avatar,
        string $user_id,
        string $content,
        int $rate,
        string $upload_date,
        ?string $accept_date,
        ?string $reject_date
    ) {
        $this->id = $id;
        $this->book_id = $book_id;
        $this->user_name = $user_name;
        $this->user_avatar = $user_avatar;
        $this->user_id = $user_id;
        $this->content = $content;
        $this->rate = $rate;
        $this->upload_date = $upload_date;
        $this->accept_date = $accept_date;
        $this->reject_date = $reject_date;

    }
    public function getId()
    {
        return $this->id;
    }

    public function getBookId()
    {
        return $this->book_id;
    }

    public function getUserName()
    {
        return $this->user_name;
    }

    public function getUserAvatar()
    {
        return $this->user_avatar;
    }

    public function getUserId()
    {
        return $this->user_id;
    }
    public function getContent()
    {
        return $this->content;
    }



    public function getRate()
    {
        return $this->rate;
    }




    public function getUploadDate()
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->upload_date);
        return $dt->format('d.m.Y') . 'r.';
    }

    public function getAcceptDate()
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->accept_date);
        return $dt->format('d.m.Y') . 'r.';
    }



    public function getRejectDate()
    {
        return $this->reject_date;
    }






}



