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

        $errors = [];

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUser($email);



        if (!$user) {
            $errors['email'] = 'User with this email does not exist!';
            return $this->render(
                'login',
                [
                    'errors' => $errors,
                ],
            );
        }

        if (!password_verify($password, $user->getPassword())) {
            $errors['password'] = 'Wrong password!';
            return $this->render(
                'login',
                [
                    'errors' => $errors,
                ],
            );
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
        $errors = [];

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['repeat-password'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];


        // Validate all fields for non-emptiness
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        }

        if (empty($confirmedPassword)) {
            $errors['confirmedPassword'] = 'Please confirm your password.';
        }

        if (empty($name)) {
            $errors['name'] = 'Name is required.';
        }

        if (empty($surname)) {
            $errors['surname'] = 'Surname is required.';
        }

        if ($password !== $confirmedPassword) {
            $errors['confirmedPassword'] = 'Passwords do not match.';
        }

        // If there are errors, render the register page with error messages
        if (!empty($errors)) {
            return $this->render('register', ['errors' => $errors]);
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
