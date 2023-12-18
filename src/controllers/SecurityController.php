<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/user/User.php';
require_once __DIR__ . '/../models/user/UserWriteRequest.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private $userRepository;


    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }


    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');

        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUser($email);



        if (!$user) {
            return $this->render('login', ['messages' => ['User not found!']]);
        }

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email not exist!']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }


        $data = Session::getInstance();
        $data->__set('user-email', $user->getEmail());
        $data->__set('user-id', $user->getId());
        $data->__set('is-logged', true);


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['repeat-password'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        if ($password !== $confirmedPassword) {
            return $this->render('register', ['messages' => ['Please provide proper password']]);
        }

        $userWriteRequest = new UserWriteRequest($email, password_hash($password, PASSWORD_BCRYPT), $name, $surname);
        $this->userRepository->addUser($userWriteRequest);
        $user = $this->userRepository->getUser($email);


        $data = Session::getInstance();
        $data->__set('user-email', $user->getEmail());
        $data->__set('user-id', $user->getId());
        $data->__set('is-logged', true);


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
        return;
    }

    public function logout()
    {
        $data = Session::getInstance();
        $data->destroy();
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
        return;
    }








}
