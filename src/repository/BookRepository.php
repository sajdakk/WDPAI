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
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($books as $book) {
            $result[] = new Book(
                $book['id'],
                $book['title'],
                $book['genre_id'],
                $book['language_id'],
                $book['date_of_publication'],
                $book['page_count'],
                $book['image'],
                $book['isbn_number'],
                $book['description'],
                $book['upload_date'],
                $book['accept_date'],
                $book['created_by'],
                $book['reject_date']

            );
        }

        return $result;
    }
    public function getMayInterestYouBooks(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        b.*,
        AVG(r.rate) AS average_mark
    FROM
        books b
   LEFT JOIN
        reviews r ON b.id = r.book_id
    WHERE
        r.accept_date IS NOT NULL
    GROUP BY
        b.id
    ORDER BY
        average_mark DESC
    LIMIT
        10;
        ');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($books as $book) {
            $result[] = new Book(
                $book['id'],
                $book['title'],
                $book['genre_id'],
                $book['language_id'],
                $book['date_of_publication'],
                $book['page_count'],
                $book['image'],
                $book['isbn_number'],
                $book['description'],
                $book['upload_date'],
                $book['accept_date'],
                $book['created_by'],
                $book['reject_date']

            );
        }

        return $result;
    }
    public function getTopBooks(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        b.*,
        AVG(r.rate) AS average_mark
    FROM
        books b
    LEFT JOIN
        reviews r ON b.id = r.book_id
    GROUP BY
        b.id
    ORDER BY
        average_mark DESC
        ');
    //     $stmt = $this->database->connect()->prepare('
    //     SELECT
    //     b.*,
    //     AVG(r.rate) AS average_mark
    // FROM
    //     books b
    // JOIN
    //     reviews r ON b.id = r.book_id
    // WHERE
    //     r.accept_date IS NOT NULL
    // GROUP BY
    //     b.id
    // ORDER BY
    //     average_mark DESC
    //     ');
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($books as $book) {
            $result[] = new Book(
                $book['id'],
                $book['title'],
                $book['genre_id'],
                $book['language_id'],
                $book['date_of_publication'],
                $book['page_count'],
                $book['image'],
                $book['isbn_number'],
                $book['description'],
                $book['upload_date'],
                $book['accept_date'],
                $book['created_by'],
                $book['reject_date']

            );
        }

        return $result;
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


    public function getBookFromId(string $bookId): ?Book
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM books WHERE id = :book_id
        ');
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_STR);
        $stmt->execute();

        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book == false) {
            return null;
        }

        return new Book(
            $book['id'],
            $book['title'],
            $book['genre_id'],
            $book['language_id'],
            $book['date_of_publication'],
            $book['page_count'],
            $book['image'],
            $book['isbn_number'],
            $book['description'],
            $book['upload_date'],
            $book['accept_date'],
            $book['created_by'],
            $book['reject_date']
        );
    }
    public function getAuthorStringForBookId(int $bookId): String
    {

        //Query to get authors for book (name and surname in one string, separated by comma)
        $stmt = $this->database->connect()->prepare('
        SELECT string_agg(a.name || \' \' || a.surname, \', \') AS author_string
        FROM authors AS a
        JOIN author_book AS ba ON a.id = ba.author_id
        WHERE ba.book_id = :bookId;
        ');
        $stmt->bindParam(':bookId', $bookId, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
    
        return strval($result) ?: '';  
    }




    public function addBook(BookWriteRequest $bookWriteRequest, $authors): ?int
    {
        // Convert PHP array to a string representation suitable for PostgreSQL
        $authorsString = '{' . implode(',', $authors) . '}';

        // Convert genreId to int
        $genreId = intval($bookWriteRequest->getGenreId());

        // Convert languageId to int
        $languageId = intval($bookWriteRequest->getLanguageId());


        $stmt = $this->database->connect()->prepare('
        SELECT add_book(
          ?,
        ?,
       ?,
          ?,
          ?,
          ?,
            ?,
         ?,
       ?,
   ?,
   ?
          )
        ');

        $success = $stmt->execute([
            $bookWriteRequest->getTitle(),
            $genreId,
            $languageId,
            $bookWriteRequest->getDateOfPublication(),
            $bookWriteRequest->getPageCount(),
            $bookWriteRequest->getImage(),
            $bookWriteRequest->getIsbnNumber(),
            $bookWriteRequest->getDescription(),
            $bookWriteRequest->getUploadDate(),
            $bookWriteRequest->getCreatedBy(),
            $authorsString
        ]);

        return $success;
    }
}