<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/review/Review.php';
require_once __DIR__ . '/../models/review/ReviewToDisplay.php';
require_once __DIR__ . '/../models/review/ReviewWriteRequest.php';

class ReviewRepository extends Repository
{

    public function getReviewFromId(string $reviewId): ?Review
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM reviews WHERE id = :review_id
        ');
        $stmt->bindParam(':review_id', $reviewId, PDO::PARAM_STR);
        $stmt->execute();

        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($review == false) {
            return null;
        }

        return new Review(
            $review['id'],
            $review['book_id'],
            $review['user_id'],
            $review['content'],
            $review['rate'],
            $review['upload_date'],
            $review['accept_date'],
            $review['reject_date']

        );
    }

    public function getReviewFromUserId(string $userId)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM reviews WHERE user_id = :user_id
        ');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();





        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($reviews as $review) {
            $result[] = new Review(
                $review['id'],
                $review['book_id'],
                $review['user_id'],
                $review['content'],
                $review['rate'],
                $review['upload_date'],
                $review['accept_date'],
                $review['reject_date']
            );
        }

        return $result;
    }

    public function getReviewToDisplayFromUserId(string $userId)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT
                r.id,
                r.book_id,
                b.title AS book_title,
                a.author_string AS book_authors,
                r.user_id,
                r.content,
                r.rate,
                r.upload_date,
                r.accept_date,
                r.reject_date
            FROM reviews AS r
            JOIN books AS b ON r.book_id = b.id
            LEFT JOIN (
                SELECT
                    ba.book_id,
                    string_agg(a.name || \' \' || a.surname, \', \') AS author_string
                FROM authors AS a
                JOIN author_book AS ba ON a.id = ba.author_id
                GROUP BY ba.book_id
            ) AS a ON r.book_id = a.book_id
            WHERE r.user_id = :user_id
        ');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($reviews as $review) {
            $result[] = new ReviewToDisplay(
                $review['id'],
                $review['book_id'],
                $review['book_title'],
                $review['book_authors'],
                $review['user_id'],
                $review['content'],
                $review['rate'],
                $review['upload_date'],
                $review['accept_date'],
                $review['reject_date']
            );
        }

        return $result;
    }


    public function addReview(ReviewWriteRequest $request): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO reviews (user_id, book_id, content, rate, upload_date, accept_date, reject_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $request->getUserId(),
            $request->getBookId(),
            $request->getContent(),
            $request->getRate(),
            $request->getUploadDate(),
            $request->getAcceptDate(),
            $request->getRejectDate()
        ]);
    }
}