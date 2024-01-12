<?php

class BookToDisplay
{
    public string $id;
    public string $title;
    public string $authorsString;
    public string $average_rate;
    public string $rate_count;
    public string $genre_id;
    public string $language_id;
    public string $date_of_publication;
    public int $page_count;
    public string $image;
    public string $isbn_number;
    public string $description;
    public string $upload_date;
    public ?string $accept_date;
    public int $created_by;
    public ?string $reject_date;

    // TODO(Anitka): Remove getters and setters

    public function __construct(
        string $id,
        string $title,
        string $authorsString,
        string $average_rate,
        string $rate_count,
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
        $this->average_rate = $average_rate;
        $this->rate_count = $rate_count;
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

    public function getAverageRate()
    {
        $value = floatval($this->average_rate);
        return round($value, 1, null);
    }

    public function getDateOfPublication()
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->date_of_publication);
        return $dt->format('d.m.Y') . 'r.';
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
}



