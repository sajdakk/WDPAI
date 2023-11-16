<?php

class Book {
    private String $title;
    private String $bookType;
    private String $language;
    private String $placeOfPublication;
    private int $pageCount;
    private String $imageUrl;
    private String $description;

    public function __construct(String $title, String $bookType, String $language, String $placeOfPublication, int $pageCount, String $imageUrl, String $description) {
        $this->title = $title;
        $this->bookType = $bookType;
        $this->language = $language;
        $this->placeOfPublication = $placeOfPublication;
        $this->pageCount = $pageCount;
        $this->imageUrl = $imageUrl;
        $this->description = $description;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getBookType() {
        return $this->bookType;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getPlaceOfPublication() {
        return $this->placeOfPublication;
    }

    public function getPageCount() {
        return $this->pageCount;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public function getDescription() {
        return $this->description;
    }

}



