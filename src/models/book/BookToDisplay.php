<?php

class BookToDisplay
{
    private string $id;
    private string $title;
    private string $authorsString;
    private string $genre_id;
    private string $language_id;
    private string $date_of_publication;
    private int $page_count;
    private string $image;
    private string $isbn_number;
    private string $description;
    private string $upload_date;
    private ?string $accept_date;
    private int $created_by;
    private ?string $reject_date;

    public function __construct(
        string $id,
        string $title,
        string $authorsString,
        string $genre_id,
        string $language_id,
        string $date_of_publication,
        int $page_count,
        string $image,
        string $isbn_number,
        string $description,
        string $upload_date,
        ?string $accept_date,
        int $created_by,
        ?string $reject_date
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->authorsString = $authorsString;
        $this->genre_id = $genre_id;
        $this->language_id = $language_id;
        $this->date_of_publication = $date_of_publication;
        $this->page_count = $page_count;
        $this->image = $image;
        $this->isbn_number = $isbn_number;
        $this->description = $description;
        $this->upload_date = $upload_date;
        $this->accept_date = $accept_date;
        $this->created_by = $created_by;
        $this->reject_date = $reject_date;

    }
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthorsString()
    {
        return $this->authorsString;
    }

    public function getGenreId()
    {
        return $this->genre_id;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function getDateOfPublication()
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->date_of_publication);
        return $dt->format('d.m.Y') . 'r.';
    }

    public function getPageCount()
    {
        return $this->page_count;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getUploadDate()
    {
        return $this->upload_date;
    }

    public function getAcceptDate()
    {
        return $this->accept_date;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }

    public function getRejectDate()
    {
        return $this->reject_date;
    }
    public function getCreatedAt()
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->upload_date);
        return $dt->format('d.m.Y') . 'r.';
    }
    public function getAcceptedAt()
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->accept_date);
        return $dt->format('d.m.Y') . 'r.';
    }

    public function getIsbnNumber()
    {
        return $this->isbn_number;
    }


}



