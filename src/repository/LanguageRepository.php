<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/language/Language.php';
require_once __DIR__ . '/../models/language/LanguageWriteRequest.php';

class LanguageRepository extends Repository
{
    public function getLanguages(): array
    {


        $stmt = $this->database->connect()->prepare('
            SELECT * FROM languages;
        ');
        $stmt->execute();
        $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($languages as $language) {
            $result[] = new Language(
                $language['id'],
                $language['language']

            );
        }

        return $result;
    }
}