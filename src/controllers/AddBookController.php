<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';
require_once __DIR__ . '/../repository/GenreRepository.php';
require_once __DIR__ . '/../repository/LanguageRepository.php';
require_once __DIR__ . '/../repository/AuthorRepository.php';


class AddBookController extends AppController
{

    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    private $bookRepository;
    private $genreRepository;
    private $languageRepository;
    private $authorRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->genreRepository = new GenreRepository();
        $this->languageRepository = new LanguageRepository();
        $this->authorRepository = new AuthorRepository();
    }

    public function create()
    {
        $languages = $this->languageRepository->getLanguages();
        $genres = $this->genreRepository->getGenres();
        $authors = $this->authorRepository->getAuthors();
        $data = Session::getInstance();

        if (!$this->isPost()) {

            $this->render(
                'create',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'authors' => $authors,
                    'languages' => $languages,
                    'genres' => $genres
                ],
            );

            return;
        }

        $this->bookRepository->startTransaction();

        $user_id = $data->__get('user-id');

        $image = $_FILES['image'];
        if (!is_uploaded_file($image['tmp_name'])) {
            return $this->render(
                'create',
                [
                    'error' => 'You have to upload an image',
                    'isLogged' => $data->__get('is-logged'),
                    'authors' => $authors,
                    'languages' => $languages,
                    'genres' => $genres
                ]
            );
        }

        $imageValidationError = $this->validateFile($image);
        if ($imageValidationError) {
            return $this->render(
                'create',
                [
                    'error' => $imageValidationError,
                    'isLogged' => $data->__get('is-logged'),
                    'authors' => $authors,
                    'languages' => $languages,
                    'genres' => $genres
                ]
            );
        }

        move_uploaded_file(
            $image['tmp_name'],
            dirname(__DIR__) . self::UPLOAD_DIRECTORY . $image['name']
        );

        $title = $_POST['title'];
        $language = $_POST['language'];
        $dateOfPub = $_POST['date-of-pub'];
        $pageCount = $_POST['page-count'];
        $isbnNumber = $_POST['isbn-number'];
        $description = $_POST['description'];
        $genre = $_POST['genre'];
        $selectedAuthors = $_POST['authors'];

        $book = new BookWriteRequest(
            $title,
            $genre,
            $language,
            $dateOfPub,
            $pageCount,
            $image['name'],
            $isbnNumber,
            $description,
            date('Y-m-d'),
            null,
            $user_id,
            null
        );

        $this->bookRepository->addBook($book, $selectedAuthors);


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");


    }

    private function validateFile(array $file): ?string
    {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return 'File is too large for destination file system.';
        }

        if (!isset($file['type']) || !in_array($file['type'], self::SUPPORTED_TYPES)) {
            return 'File type is not supported.';
        }

        return null;
    }

}