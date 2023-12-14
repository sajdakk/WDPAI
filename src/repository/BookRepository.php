<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/book/BookWriteRequest.php';

class BookRepository extends Repository
{
    public function getBooks(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM books;
        ');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_CLASS, Book::class);
        // $result = [];

        // foreach ($books as $book) {
        //     $result[] = new Book(
        //         $book['id'],
        //         $book['email'],
        //         $book['password'],
        //         $book['name'],
        //         $book['surname'],
        //         $book['avatar'],
        //     );
        // }

        return $books;
    }
    public function getMayInterestYouBooks(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        b.*,
        AVG(r.mark) AS average_mark
    FROM
        books b
    JOIN
        reviews r ON b.book_id = r.book_id
    WHERE
        r.accept_date IS NOT NULL
    GROUP BY
        b.book_id
    ORDER BY
        average_mark DESC
    LIMIT
        10;
        ');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_CLASS, Book::class);
        // $result = [];

        // foreach ($books as $book) {
        //     $result[] = new Book(
        //         $book['id'],
        //         $book['email'],
        //         $book['password'],
        //         $book['name'],
        //         $book['surname'],
        //         $book['avatar'],
        //     );
        // }

        return $books;
    }
    public function getTopBooks(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        b.*,
        AVG(r.mark) AS average_mark
    FROM
        books b
    JOIN
        reviews r ON b.book_id = r.book_id
    WHERE
        r.accept_date IS NOT NULL
    GROUP BY
        b.book_id
    ORDER BY
        average_mark DESC;

        ');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_CLASS, Book::class);
        // $result = [];

        // foreach ($books as $book) {
        //     $result[] = new Book(
        //         $book['id'],
        //         $book['email'],
        //         $book['password'],
        //         $book['name'],
        //         $book['surname'],
        //         $book['avatar'],
        //     );
        // }

        return $books;
    }
    public function getMFilteredBooks(?string $title, ?string $author_name, ?string $author_surname): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        b.*
    FROM
        books b
    JOIN
        author_book ab ON b.book_id = ab.book_id
    JOIN
        author a ON ab.author_id = a.id
    WHERE
        (:author_name IS NULL OR a.name = :author_name)
        AND (:author_surname IS NULL OR a.surname = :author_surname)
        AND (:title IS NULL OR b.title = :title);
    
        ');

        $stmt->bindParam(':title', $title, ':author_surname', $author_surname, ':author_name', $author_name, PDO::PARAM_STR);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_CLASS, Book::class);
        // $result = [];

        // foreach ($books as $book) {
        //     $result[] = new Book(
        //         $book['id'],
        //         $book['email'],
        //         $book['password'],
        //         $book['name'],
        //         $book['surname'],
        //         $book['avatar'],
        //     );
        // }

        return $books;
    }


    public function getBook(string $book): ?Book
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM books WHERE book = :book
        ');
        $stmt->bindParam(':book', $book, PDO::PARAM_STR);
        $stmt->execute();

        $book = $stmt->fetch(PDO::FETCH_CLASS, Book::class);

        // if ($book == false) {
        //     return null;
        // }

        // return new Book(
        //     $book['id'],
        //     $book['email'],
        //     $book['password'],
        //     $book['name'],
        //     $book['surname'],
        //     $book['avatar']
        // );
        return $book;
    }

    public function addBook(BookWriteRequest $bookWriteRequest)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO books (
            title,
            genre_id,
            language_id,
            date_of_publication,
            pageCount,
            image,
            description,
            upload_date,
            created_by)
            VALUES (?, ?, ?, ?,?,?,?,?,?,?)
        ');

        $stmt->execute([
            $bookWriteRequest->getTitle(),
            $bookWriteRequest->getGenreId(),
            $bookWriteRequest->getLanguageId(),
            $bookWriteRequest->getDateOfPublication(),
            $bookWriteRequest->getPageCount(),
            $bookWriteRequest->getImage(),
            $bookWriteRequest->getDescription(),
            $bookWriteRequest->getUploadDate(),
            $bookWriteRequest->getCreatedBy(),
        ]);


    }
}