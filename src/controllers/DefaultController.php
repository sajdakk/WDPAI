<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/favorite/Favorite.php';
require_once __DIR__ . '/../models/favorite/FavoriteWriteRequest.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/review/ReviewToDisplay.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';
require_once __DIR__ . '/../repository/GenreRepository.php';
require_once __DIR__ . '/../repository/LanguageRepository.php';
require_once __DIR__ . '/../repository/FavoriteRepository.php';
require_once __DIR__ . '/../repository/ReviewRepository.php';


class DefaultController extends AppController
{

    private $bookRepository;
    private $genreRepository;
    private $languageRepository;
    private $favoriteRepository;
    private $userRepository;
    private $reviewRepository;


    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';


    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->genreRepository = new GenreRepository();
        $this->languageRepository = new LanguageRepository();
        $this->favoriteRepository = new FavoriteRepository();
        $this->userRepository = new UserRepository();
        $this->reviewRepository = new ReviewRepository();
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

        if (!$userId) {
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
        header("Location: $source");
    }

    public function addReview()
    {
        if (!$this->isPost()) {
            return;
        }

        $data = Session::getInstance();
        $userId = $data->__get('user-id');


        if (!$userId) {
            return;
        }

        $source = $_SERVER["HTTP_REFERER"];
        $parts = explode("/", $source);

        $bookId = end($parts);
        $content = $_POST['review'];
        $rate = $_POST['rate'];


        $this->reviewRepository->addReview(
            new ReviewWriteRequest(
                $bookId,
                $userId,
                $content,
                intval($rate),
                date('Y-m-d H:i:s'),
                null,
                null
            )
        );

        $source = $_SERVER["HTTP_REFERER"];
        header("Location: $source");
    }

    public function changeAvatar()
    {
        if (!$this->isPost()) {
            return;
        }

        $data = Session::getInstance();
        $userId = $data->__get('user-id');


        if (!$userId) {
            return;
        }

        $image = $_FILES['image'];
        $avatar = $data->__get('user-avatar');

        if (!is_uploaded_file($image['tmp_name'])) {


            return $this->render(
                'profile',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'avatar' => $avatar,
                    'error' => 'You have to upload an image'

                ],
            );


        }


        $imageValidationError = $this->validateFile($image);
        if ($imageValidationError) {
            return $this->render(
                'profile',
                [
                    'error' => $imageValidationError,
                    'isLogged' => $data->__get('is-logged'),
                    'avatar' => $avatar,
                ]
            );
        }

        move_uploaded_file(
            $image['tmp_name'],
            dirname(__DIR__) . self::UPLOAD_DIRECTORY . $image['name']
        );


        $newAvatar = $image['name'];

        $this->userRepository->updateAvatarWithUserId($newAvatar, $userId);


        $source = $_SERVER["HTTP_REFERER"];
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

    public function profile()
    {
        $data = Session::getInstance();

        if (!$data->__get('is-logged')) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/");
            return;
        }

        $email = $data->__get('user-email');

        $user = $this->userRepository->getUser($email);

        $reviews = $this->reviewRepository->getReviewToDisplayFromUserId($user->getId());

        $books = $this->bookRepository->getBooksToDisplayFromUserId($user->getId());




        $this->render(
            'profile',
            [
                'isLogged' => $data->__get('is-logged'),
                'avatar' => $user->getAvatar(),
                'username' => $user->getName(),
                'reviews' => $reviews,
                'books' => $books
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


        $email = $data->__get('user-email');

        $user = $this->userRepository->getUser($email);



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
                'userAvatar' => $user->getAvatar()
            ],
        );

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