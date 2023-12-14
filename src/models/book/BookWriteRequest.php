<?php

class BookWriteRequest
{
    private string $title;
    private string $genre_id;
    private string $language_id;
    private string $date_of_publication;
    private int $pageCount;
    private string $image;
    private string $isbn_number;
    private string $description;
    private string $upload_date;
    private ?string $accept_date;
    private int $created_by;


    public function __construct(string $title, string $genre_id, string $language_id, string $date_of_publication, int $pageCount, string $image, string $description, string $upload_date, int $accept_date, int $created_by)
    {
        $this->title = $title;
        $this->bookType = $genre_id;
        $this->language = $language_id;
        $this->placeOfPublication = $date_of_publication;
        $this->pageCount = $pageCount;
        $this->imageUrl = $image;
        $this->description = $description;
        $this->upload_date = $upload_date;
        $this->accept_date = $accept_date;
        $this->created_by = $created_by;

    }

    public function getTitle()
    {
        return $this->title;
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
        return $this->date_of_publication;
    }

    public function getPageCount()
    {
        return $this->pageCount;
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


}



