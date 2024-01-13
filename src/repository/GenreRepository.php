<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/genre/Genre.php';
require_once __DIR__ . '/../models/genre/GenreWriteRequest.php';

class GenreRepository extends Repository
{
    public function getGenres(): array
    {


        $stmt = $this->database::getInstance()->connect()->prepare('
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

    public function getGenreFromId(string $genreId): ?Genre
    {
        $stmt = $this->database::getInstance()->connect()->prepare('
            SELECT * FROM genres WHERE id = :genre_id
        ');
        $stmt->bindParam(':genre_id', $genreId, PDO::PARAM_STR);
        $stmt->execute();

        $genre = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($genre == false) {
            return null;
        }

        return new Genre(
            $genre['id'],
            $genre['title']

        );
    }
}