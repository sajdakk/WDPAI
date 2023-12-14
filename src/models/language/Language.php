<?php

class Language
{
    private string $id;
    private string $language;

    public function __construct(string $id, string $language)
    {
        $this->id = $id;
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }


    public function getId()
    {
        return $this->id;
    }

}



