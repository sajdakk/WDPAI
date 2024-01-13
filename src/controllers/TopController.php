<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/favorite/Favorite.php';
require_once __DIR__ . '/../models/favorite/FavoriteWriteRequest.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/review/ReviewToDisplay.php';
require_once __DIR__ . '/../models/review/ReviewToCreatedByDisplay.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';
require_once __DIR__ . '/../repository/FavoriteRepository.php';


class TopController extends AppController
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
        $user = $this->userRepository->getUserWithId($userId);


        $this->render(
            'top',
            [
                'isLogged' => $data->__get('is-logged'),
                'books' => $books,
                'favorites' => $favorites,
                'isAdmin' => $user->getRole() == 'admin'

            ],
        );
    }
}