<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';
require_once __DIR__ . '/../repository/GenreRepository.php';
require_once __DIR__ . '/../repository/LanguageRepository.php';


class AddBookController extends AppController
{

    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    private $bookRepository;
    private $genreRepository;
    private $languageRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->genreRepository = new GenreRepository();
        $this->languageRepository = new LanguageRepository();
    }

    public function create()
    {
        $authors = array(
           

        );

        $languages = $this->languageRepository->getLanguages();
        $genres = $this->genreRepository->getGenres();

        $data = Session::getInstance();

        $this->render(
            'create',
            [
                'isLogged' => $data->__get('is-logged'),
                'authors' => $authors,
                'languages' => $languages,
                'genres'=> $genres
            ],
        );
    }

}