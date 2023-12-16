<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/favorite/Favorite.php';
require_once __DIR__ . '/../models/favorite/FavoriteWriteRequest.php';

class FavoriteRepository extends Repository
{

    public function getFavoriteFromId(string $favoriteId): ?Favorite
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM favorites WHERE id = :favorite_id
        ');
        $stmt->bindParam(':favorite_id', $favoriteId, PDO::PARAM_STR);
        $stmt->execute();

        $favorite = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($favorite == false) {
            return null;
        }

        return new Favorite(
            $favorite['id'],
            $favorite['user_id'],
            $favorite['book_id']

        );
    }

    public function getFavoriteFromUserId(string $userId)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM favorites WHERE user_id = :user_id
        ');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();





        $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($favorites as $favorite) {
            $result[] = new Favorite(
                $favorite['id'],
                $favorite['user_id'],
                $favorite['book_id'],
            );
        }

        return $result;
    }

    public function addFavorite(FavoriteWriteRequest $request): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO favorite (user_id, book_id)
            VALUES (?, ?)
        ');

        $stmt->execute([
            $request->getUserId(),
            $request->getBookId()
        ]);
    }
}