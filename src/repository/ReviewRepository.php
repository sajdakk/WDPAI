<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/review/Review.php';
require_once __DIR__ . '/../models/review/ReviewToDisplay.php';
require_once __DIR__ . '/../models/review/ReviewWriteRequest.php';
require_once __DIR__ . '/../models/review/ReviewToCreatedByDisplay.php';

class ReviewRepository extends Repository
{

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

    public function hasUserReviewedBook(string $userId, string $bookId): bool
    {
        $stmt = $this->database->connect()->prepare('
            SELECT COUNT(*) as review_count
            FROM reviews
            WHERE user_id = :user_id AND book_id = :book_id AND reject_date IS NULL
        ');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user has added a review for the given book
        return ($result['review_count'] > 0);
    }

    public function getReviewToDisplayForBookId(string $bookId)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT
                r.id,
                r.book_id,
               u.name as user_name,
               u.avatar as user_avatar,
                r.user_id,
                r.content,
                r.rate,
                r.upload_date,
                r.accept_date,
                r.reject_date
            FROM reviews AS r
            JOIN users AS u ON r.user_id = u.id
            WHERE r.book_id = :book_id AND  r.accept_date IS NOT NULL
            ORDER BY
    r.upload_date DESC;

        ');
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_STR);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($reviews as $review) {
            $result[] = new ReviewToCreatedByDisplay
            (
                $review['id'],
                $review['book_id'],
                $review['user_name'],
                $review['user_avatar'],
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

    public function getReviewToDisplayForAdmin()
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
        WHERE r.accept_date IS NULL AND r.reject_date IS NULL
    ');
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


    public function acceptReviewForReviewId(string $reviewId): bool
    {
        $stmt = $this->database->connect()->prepare('
        UPDATE reviews
        SET accept_date = CURRENT_TIMESTAMP
        WHERE id = :review_id
    ');

        $stmt->bindParam(':review_id', $reviewId, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return true; // Successful update
        } catch (PDOException $e) {
            // Handle the exception or log the error
            return false; // Update failed
        }
    }

    public function rejectReviewForReviewId(string $reviewId): bool
    {
        $stmt = $this->database->connect()->prepare('
        UPDATE reviews
        SET reject_date = CURRENT_TIMESTAMP
        WHERE id = :review_id
    ');

        $stmt->bindParam(':review_id', $reviewId, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return true; // Successful update
        } catch (PDOException $e) {
            // Handle the exception or log the error
            return false; // Update failed
        }
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