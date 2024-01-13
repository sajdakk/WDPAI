<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/favorite/Favorite.php';
require_once __DIR__ . '/../models/favorite/FavoriteWriteRequest.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/review/ReviewToDisplay.php';
require_once __DIR__ . '/../models/review/ReviewToCreatedByDisplay.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';
require_once __DIR__ . '/../repository/ReviewRepository.php';
require_once __DIR__ . '/../validators/UploadImageValidator.php';


class ProfileController extends AppController
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
                'books' => $books,
                'isAdmin' => $user->getRole() == 'admin'
            ],
        );
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


        $imageValidationError = UploadImageValidator::validateFile($image);
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
            dirname(__DIR__) . UploadImageValidator::UPLOAD_DIRECTORY . $image['name']
        );


        $newAvatar = $image['name'];

        $this->userRepository->updateAvatarWithUserId($newAvatar, $userId);


        $source = $_SERVER["HTTP_REFERER"];
        header("Location: $source");
    }

}