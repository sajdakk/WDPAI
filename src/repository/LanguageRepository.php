<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/language/Language.php';
require_once __DIR__ . '/../models/language/LanguageWriteRequest.php';

class LanguageRepository extends Repository
{
    public function getLanguages(): array
    {


        $stmt = $this->database::getInstance()->connect()->prepare('
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

    public function getLanguageFromId(string $languageId): ?Language
    {
        $stmt = $this->database::getInstance()->connect()->prepare('
            SELECT * FROM languages WHERE id = :language_id
        ');
        $stmt->bindParam(':language_id', $languageId, PDO::PARAM_STR);
        $stmt->execute();

        $language = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($language == false) {
            return null;
        }

        return new Language(
            $language['id'],
            $language['language']

        );
    }
}