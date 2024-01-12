<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/author/AuthorWriteRequest.php';

class AuthorRepository extends Repository
{
    public function getAuthors(): array
    {


        $stmt = $this->database->connect()->prepare('
            SELECT * FROM authors;
        ');
        $stmt->execute();
        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($authors as $author) {
            $result[] = new Author(
                $author['id'],
                $author['name'],
                $author['surname']

            );
        }

        return $result;
    }
}