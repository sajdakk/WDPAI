<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/favorite/Favorite.php';
require_once __DIR__ . '/../models/favorite/FavoriteWriteRequest.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/review/ReviewToDisplay.php';
require_once __DIR__ . '/../models/review/ReviewToCreatedByDisplay.php';
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

        $data = Session::getInstance();
        $title = $_POST['title'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        $isEmpty = empty($title) && empty($name) && empty($surname);

        $userId = $data->__get('user-id');
        $favorites = $userId ? $this->favoriteRepository->getFavoriteFromUserId($userId) : [];


        if ($this->isPost() && !$isEmpty) {
            $filtered = $this->bookRepository->getFilteredBooks($title, $name, $surname);

            $this->render(
                'dashboard',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'filtered' => $filtered,
                    'mayInterestYou' => false,
                    'books' => $filtered,
                    'favorites' => $favorites,
                    'initialTitle' => $title,
                    'initialName' => $name,
                    'initialSurname' => $surname

                ],
            );
            return;
        }

        $mayInterestYou = $this->bookRepository->getMayInterestYouBooks();


        if (!$userId) {
            $this->render(
                'dashboard',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'mayInterestYou' => !empty($mayInterestYou),
                    'books' => $mayInterestYou,
                    'favorites' => [],
                    'initialTitle' => $title,
                    'initialName' => $name,
                    'initialSurname' => $surname
                ],
            );
            return;
        }

        $this->render(
            'dashboard',
            [
                'isLogged' => $data->__get('is-logged'),
                'mayInterestYou' => !empty($mayInterestYou),
                'books' => $mayInterestYou,
                'favorites' => $favorites,
                'initialTitle' => $title,
                'initialName' => $name,
                'initialSurname' => $surname
            ],
        );
    }
    public function top()
    {
        $books = $this->bookRepository->getTopBooks();


        $data = Session::getInstance();
        $userId = $data->__get('user-id');


        if (empty($userId)) {
            $this->render(
                'top',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'books' => $books,
                    'favorites' => []
                ],
            );

            return;
        }

        $favorites = $this->favoriteRepository->getFavoriteFromUserId($userId);

        $this->render(
            'top',
            [
                'isLogged' => $data->__get('is-logged'),
                'books' => $books,
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
    public function toggleReviewStatus()
    {
        if (!$this->isPost()) {
            return;
        }

        $reviewId = $_POST['review-id'];
        $action = $_POST['action'];

        if ($action == 'accept') {
            $this->reviewRepository->acceptReviewForReviewId($reviewId);
        } else if ($action == 'reject') {
            $this->reviewRepository->rejectReviewForReviewId($reviewId);
        }

        $source = $_SERVER["HTTP_REFERER"];
        header("Location: $source");
    }
    public function toggleBookStatus()
    {
        if (!$this->isPost()) {
            return;
        }

        $bookId = $_POST['book-id'];
        $action = $_POST['action'];

        if ($action == 'accept') {
            $this->bookRepository->acceptBookForBookId($bookId);
        } else if ($action == 'reject') {
            $this->bookRepository->rejectBookForBookId($bookId);
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

    public function registration()
    {
        $this->render('registration');
    }

    public function admin()
    {
        $data = Session::getInstance();

        $reviews = $this->reviewRepository->getReviewToDisplayForAdmin();

        $books = $this->bookRepository->getBooksToDisplayForAdmin();


        $this->render(
            'admin',
            [
                'isLogged' => $data->__get('is-logged'),
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
        $reviews = $this->reviewRepository->getReviewToDisplayForBookId($params);
        $average = 0;
        foreach ($reviews as $review) {
            $average += $review->getRate();
        }

        if (count($reviews) > 0) {
            $average = $average / count($reviews);
        }



        $authorString = $this->bookRepository->getAuthorStringForBookId($book->getId());
        $genre = $this->genreRepository->getGenreFromId($book->getGenreId());
        $language = $this->languageRepository->getLanguageFromId($book->getLanguageId());

        $date = new DateTime();
        $isLogged = $data->__get('is-logged');

        if (!$isLogged) {
            $this->render(
                'details',
                [
                    'isLogged' => $$isLogged,
                    'book' => $book,
                    'authorString' => $authorString,
                    'genreString' => $genre->getGenre(),
                    'languageString' => $language->getLanguage(),
                    'userName' => '',
                    'nowString' => $date->format('d.m.Y') . 'r.',
                    'userAvatar' => '',
                    'reviews' => $reviews,
                    'average' => $average,

                ],
            );
            return;
        }



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
                'userName' => $user->getName(),
                'nowString' => $date->format('d.m.Y') . 'r.',
                'userAvatar' => $user->getAvatar(),
                'reviews' => $reviews,
                'average' => $average,

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