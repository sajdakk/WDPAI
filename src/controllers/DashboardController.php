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
require_once __DIR__ . '/../repository/UserRepository.php';


class DashboardController extends AppController
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


        $user = $this->userRepository->getUserWithId($userId);

        if ($user == null) {
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
                'initialSurname' => $surname,
                'isAdmin' => $user->getRole() == 'admin'
            ],
        );
    }




}