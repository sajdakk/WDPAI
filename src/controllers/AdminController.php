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
require_once __DIR__ . '/../repository/ReviewRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';


class AdminController extends AppController
{

    private $bookRepository;
    private $userRepository;
    private $reviewRepository;


    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
        $this->userRepository = new UserRepository();
        $this->reviewRepository = new ReviewRepository();
        $this->securityController = new SecurityController();
    }


    public function admin()
    {

        $data = Session::getInstance();
        $userId = $data->__get('user-id');
        $user = $this->userRepository->getUserWithId($userId);

        if (!$user || $user->getRole() != 'admin') {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/");
            return;
        }


        $data = Session::getInstance();

        $reviews = $this->reviewRepository->getReviewToDisplayForAdmin();

        $books = $this->bookRepository->getBooksToDisplayForAdmin();

        $users = $this->userRepository->getUsers();


        $this->render(
            'admin',
            [
                'isLogged' => $data->__get('is-logged'),
                'currentUserId' => $data->__get('user-id'),
                'reviews' => $reviews,
                'books' => $books,
                'users' => $users
            ],
        );
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
    }

    public function toggleUserStatus()
    {
        if (!$this->isPost()) {
            return;
        }

        $userId = trim($_POST['user-id']);
        $action = trim($_POST['action']);

        switch ($action) {
            case 'removeUser':
                $this->userRepository->removeUserWithId($userId);
                break;

            case 'removeAdmin':
                $this->userRepository->removeAdminToUserId($userId);
                break;

            case 'addAdmin':
                $this->userRepository->addAdminToUserId($userId);
                break;
        }

        echo json_encode([
            'isAdmin' => $action == 'addAdmin'
        ]);
    }
}