<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';
require_once __DIR__ . '/../repository/GenreRepository.php';
require_once __DIR__ . '/../repository/LanguageRepository.php';
require_once __DIR__ . '/../repository/AuthorRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../validators/UploadImageValidator.php';



class AddBookController extends AppController
{


    private $bookRepository;
    private $genreRepository;
    private $languageRepository;
    private $authorRepository;
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->genreRepository = new GenreRepository();
        $this->languageRepository = new LanguageRepository();
        $this->authorRepository = new AuthorRepository();
        $this->userRepository = new UserRepository();
    }

    public function create()
    {
        $languages = $this->languageRepository->getLanguages();
        $genres = $this->genreRepository->getGenres();
        $authors = $this->authorRepository->getAuthors();
        $data = Session::getInstance();

        $userId = $data->__get('user-id');




        if (!$this->isPost()) {

            if (!$userId) {
                $this->render(
                    'create',
                    [
                        'isLogged' => $data->__get('is-logged'),
                        'authors' => $authors,
                        'languages' => $languages,
                        'genres' => $genres
                    ],
                );
            } else {
                $user = $this->userRepository->getUserWithId($userId);


                $this->render(
                    'create',
                    [
                        'isLogged' => $data->__get('is-logged'),
                        'authors' => $authors,
                        'languages' => $languages,
                        'genres' => $genres,
                        'isAdmin' => $user->getRole() === 'admin'

                    ],
                );
            }



            return;
        }

        $errors = [];
        $user_id = $data->__get('user-id');

        $image = $_FILES['image'];
        if (!is_uploaded_file($image['tmp_name'])) {
            $errors['image'] = 'You have to upload an image';

            return $this->render(
                'create',
                [
                    'errors' => $errors,
                    'isLogged' => $data->__get('is-logged'),
                    'authors' => $authors,
                    'languages' => $languages,
                    'genres' => $genres
                ]
            );
        }

        $imageValidationError = UploadImageValidator::validateFile($image);
        if ($imageValidationError) {
            $errors['image'] = $imageValidationError;
            return $this->render(
                'create',
                [
                    'error' => $errors,
                    'isLogged' => $data->__get('is-logged'),
                    'authors' => $authors,
                    'languages' => $languages,
                    'genres' => $genres
                ]
            );
        }

        move_uploaded_file(
            $image['tmp_name'],
            dirname(__DIR__) . UploadImageValidator::UPLOAD_DIRECTORY . $image['name']
        );



        // Validate title
        $title = $_POST['title'];
        if (empty($title)) {
            $errors['title'] = 'Title is required';
        }

        // Validate language
        $language = $_POST['language'];
        if (empty($language)) {
            $errors['language'] = 'Language is required';
        }

        // Validate date of publication
        $dateOfPub = $_POST['date-of-pub'];
        if (empty($dateOfPub)) {
            $errors['date-of-pub'] = 'Date of publication is required';
        }

        // Validate page count
        $pageCount = $_POST['page-count'];
        if (empty($pageCount) || !is_numeric($pageCount) || $pageCount <= 0) {
            $errors['page-count'] = 'Page count must be a positive number';
        }

        // Validate ISBN number
        $isbnNumber = $_POST['isbn-number'];
        if (empty($isbnNumber)) {
            $errors['isbn-number'] = 'ISBN number is required';
        }

        // Validate description
        $description = $_POST['description'];
        if (empty($description)) {
            $errors['description'] = 'Description is required';
        }

        // Validate genre
        $genre = $_POST['genre'];
        if (empty($genre)) {
            $errors['genre'] = 'Genre is required';
        }

        // Validate authors
        $selectedAuthors = $_POST['authors'];
        if (empty($selectedAuthors)) {
            $errors['authors'] = 'At least one author must be selected';
        }

        if (!empty($errors)) {
            return $this->render(
                'create',
                [
                    'errors' => $errors,
                    'isLogged' => $data->__get('is-logged'),
                    'authors' => $authors,
                    'languages' => $languages,
                    'genres' => $genres
                ]
            );
        }


        $book = new BookWriteRequest(
            $title,
            $genre,
            $language,
            $dateOfPub,
            $pageCount,
            $image['name'],
            $isbnNumber,
            $description,
            date('Y-m-d H:i:s'),
            null,
            $user_id,
            null
        );

        $this->bookRepository->addBook($book, $selectedAuthors);


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");


    }


}