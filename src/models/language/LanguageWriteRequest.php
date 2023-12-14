<?php

class LanguageWriteRequest
{
    private string $language;

    public function __construct( string $language)
    {
        $this->name = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }
}



