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
require_once __DIR__ . '/../repository/FavoriteRepository.php';


class FavoritesController extends AppController
{

    private $bookRepository;
    private $favoriteRepository;
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->favoriteRepository = new FavoriteRepository();
        $this->userRepository = new UserRepository();
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

            return;
        }


        $favorites = $this->favoriteRepository->getFavoriteFromUserId($userId);
        $books = [];
        foreach ($favorites as $favorite) {
            $book = $this->bookRepository->getBookFromId($favorite->getBookId());
            array_push($books, $book);
        }

        $user = $this->userRepository->getUserWithId($userId);

        $this->render(
            'favorites',
            [
                'isLogged' => $data->__get('is-logged'),
                'books' => $books,
                'favorites' => $favorites,
                'isAdmin' => $user->getRole() == 'admin'
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


}