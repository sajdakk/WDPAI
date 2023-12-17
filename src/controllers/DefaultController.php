<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/favorite/Favorite.php';
require_once __DIR__ . '/../models/favorite/FavoriteWriteRequest.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';
require_once __DIR__ . '/../repository/GenreRepository.php';
require_once __DIR__ . '/../repository/LanguageRepository.php';
require_once __DIR__ . '/../repository/FavoriteRepository.php';


class DefaultController extends AppController
{

    private $bookRepository;
    private $genreRepository;
    private $languageRepository;
    private $favoriteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->genreRepository = new GenreRepository();
        $this->languageRepository = new LanguageRepository();
        $this->favoriteRepository = new FavoriteRepository();
    }


    function login()
    {
        if ($this->isGet()) {
            return $this->render('login');
        }

        if ($this->isPost()) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            return;
        }
    }

    public function dashboard()
    {


        $books = $this->bookRepository->getMayInterestYouBooks();
        $bookIdToAuthors = array();

        foreach ($books as $book) {
            $bookId = $book->getId();
            $bookIdToAuthors[$bookId] = $this->bookRepository->getAuthorStringForBookId($bookId);
        }

        

        $data = Session::getInstance();
        $userId = $data->__get('user-id');

        if(!$userId){
            $this->render(
                'dashboard',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'books' => $books,
                    'bookIdToAuthors' => $bookIdToAuthors
                ],
            );
            return;
        }

        $favorites = $this->favoriteRepository->getFavoriteFromUserId($userId);


        $this->render(
            'dashboard',
            [
                'isLogged' => $data->__get('is-logged'),
                'books' => $books,
                'bookIdToAuthors' => $bookIdToAuthors,
                'favorites' => $favorites
            ],
        );
    }
    public function top()
    {
        $books = $this->bookRepository->getTopBooks();
        $bookIdToAuthors = array();

        foreach ($books as $book) {
            $bookId = $book->getId();
            $bookIdToAuthors[$bookId] = $this->bookRepository->getAuthorStringForBookId($bookId);
        }

        $data = Session::getInstance();
        $userId = $data->__get('user-id');


        if (!$userId) {
            $this->render(
                'top',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'books' => $books,
                    'bookIdToAuthors' => $bookIdToAuthors,
                    'favorites' => []
                ],
            );
        }

        $favorites = $this->favoriteRepository->getFavoriteFromUserId($userId);

        $this->render(
            'top',
            [
                'isLogged' => $data->__get('is-logged'),
                'books' => $books,
                'bookIdToAuthors' => $bookIdToAuthors,
                'favorites' => $favorites
            ],
        );
    }

    public function toggleFavorite()
    {
        if (!$this->isPost()) {
            return;
        }

        $data = Session::getInstance();
        $userId = $data->__get('user-id');

        $bookId = $_POST['book-id'];

        if (!$userId) {
            return;
        }

        $existFavorite = $this->favoriteRepository->getFavoriteFromUserIdAndBookId($userId, $bookId);
        if ($existFavorite == null) {
            $this->favoriteRepository->addFavorite(
                new FavoriteWriteRequest(
                    $userId,
                    $bookId
                )
            );
        } else {
            $this->favoriteRepository->removeFavoriteWithId($existFavorite->getId());
        }

        $source = $_SERVER["HTTP_REFERER"];

        // $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: $source");
    }

    public function favorites()
    {
        $data = Session::getInstance();

        $userId = $data->__get('user-id');

        if (!$userId) {
            $this->render(
                'favorites',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'books' => [],
                    'bookIdToAuthors' => []
                ],
            );
        }
        

        $favorites = $this->favoriteRepository->getFavoriteFromUserId($userId);
        $books = [];
        foreach ($favorites as $favorite) {
            $book = $this->bookRepository->getBookFromId($favorite->getBookId());
            array_push($books, $book);
        }

        foreach ($books as $book) {
            $bookId = $book->getId();
            $bookIdToAuthors[$bookId] = $this->bookRepository->getAuthorStringForBookId($bookId);
        }

        $this->render(
            'favorites',
            [
                'isLogged' => $data->__get('is-logged'),
                'books' => $books,
                'bookIdToAuthors' => $bookIdToAuthors,
                'favorites' => $favorites
            ],
        );
    }

    public function registration()
    {
        $this->render('registration');
    }

    public function profile()
    {
        $data = Session::getInstance();

        $this->render(
            'profile',
            [
                'isLogged' => $data->__get('is-logged'),
            ],
        );
    }

    public function details(string $params)
    {
        $data = Session::getInstance();

        if (strlen($params) == 0) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/");
            return;
        }

        $book = $this->bookRepository->getBookFromId($params);

        $authorString = $this->bookRepository->getAuthorStringForBookId($book->getId());
        $genre = $this->genreRepository->getGenreFromId($book->getGenreId());
        $language = $this->languageRepository->getLanguageFromId($book->getLanguageId());

        $date = new DateTime();



        $this->render(
            'details',
            [
                'isLogged' => $data->__get('is-logged'),
                'book' => $book,
                'authorString' => $authorString,
                'genreString' => $genre->getGenre(),
                'languageString' => $language->getLanguage(),
                'userName' => $data->__get('user-name'),
                'nowString' => $date->format('d.m.Y') . 'r.',
            ],
        );

    }
}