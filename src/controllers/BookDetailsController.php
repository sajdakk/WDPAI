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
require_once __DIR__ . '/../repository/ReviewRepository.php';


class BookDetailsController extends AppController
{

    private $bookRepository;
    private $genreRepository;
    private $languageRepository;
    private $userRepository;
    private $reviewRepository;


    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->genreRepository = new GenreRepository();
        $this->languageRepository = new LanguageRepository();
        $this->userRepository = new UserRepository();
        $this->reviewRepository = new ReviewRepository();
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
                    'genreString' => $genre->getGenre(),
                    'languageString' => $language->getLanguage(),
                    'userName' => '',
                    'nowString' => $date->format('d.m.Y') . 'r.',
                    'userAvatar' => '',
                    'reviews' => $reviews,


                ],
            );
            return;
        }



        $email = $data->__get('user-email');

        $user = $this->userRepository->getUser($email);

        $hasAlreadyReview = $this->reviewRepository->hasUserReviewedBook($user->getId(), $book->getId());

        $this->render(
            'details',
            [
                'isLogged' => $data->__get('is-logged'),
                'book' => $book,
                'genreString' => $genre->getGenre(),
                'languageString' => $language->getLanguage(),
                'userName' => $user->getName(),
                'nowString' => $date->format('d.m.Y') . 'r.',
                'userAvatar' => $user->getAvatar(),
                'reviews' => $reviews,
                'isAdmin' => $user->getRole() == 'admin',
                'hasAlreadyReview' => $hasAlreadyReview

            ],
        );

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
}