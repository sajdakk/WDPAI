<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/genre/Genre.php';
require_once __DIR__ . '/../models/genre/GenreWriteRequest.php';

class GenreRepository extends Repository
{
    public function getGenres(): array
    {


        $stmt = $this->database->connect()->prepare('
            SELECT * FROM genres;
        ');
        $stmt->execute();
        $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($genres as $genre) {
            $result[] = new Genre(
                $genre['id'],
                $genre['title']

            );
        }

        return $result;
    }
}