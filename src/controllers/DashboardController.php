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

        $userId = $data->__get('user-id');
        $favorites = $userId ? $this->favoriteRepository->getFavoriteFromUserId($userId) : [];



        $mayInterestYou = $this->bookRepository->getMayInterestYouBooks();


        if (!$userId) {
            $this->render(
                'dashboard',
                [
                    'isLogged' => $data->__get('is-logged'),
                    'mayInterestYou' => !empty($mayInterestYou),
                    'books' => $mayInterestYou,
                    'favorites' => [],
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
                'isAdmin' => $user->getRole() == 'admin'
            ],
        );
    }

    public function search()
    {
        if (!$this->isPost()) {
            echo '[]';

            return;
        }

        $data = Session::getInstance();
        $userId = $data->__get('user-id');

        $title = $_POST['title'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        $isEmpty = empty($title) && empty($name) && empty($surname);

        if ($isEmpty) {
            $filtered = $this->bookRepository->getMayInterestYouBooks();
        } else {
            $filtered = $this->bookRepository->getFilteredBooks($title, $name, $surname);
        }

        if ($userId) {
            $favorites = $this->favoriteRepository->getFavoriteFromUserId($userId);

            foreach ($filtered as $filter) {
                $contains = false;
                foreach ($favorites as $favorite) {
                    if ($favorite->getBookId() == $filter->id) {
                        $contains = true;
                        break;
                    }
                }

                $filter->isFavorite = $contains;
            }
        }

        echo json_encode($filtered);
    }




}