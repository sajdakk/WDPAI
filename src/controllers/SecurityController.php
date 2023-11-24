<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Session.php';

class SecurityController extends AppController
{
    static private $users = [];

    public function __construct()
    {
        parent::__construct();
    }


    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $user = new User('jsnow@pk.edu.pl', password_hash('admin', PASSWORD_BCRYPT), 'Johnny', 'Snow');

        $email = $_POST['email'];
        $password = $_POST['password'];
        $isInDatabase = false;

        if ($user->getEmail() === $email && password_verify($password, $user->getPassword())) {
            $isInDatabase = true;
        }

        // foreach (self::$users as $user) {
        //     if ($user->getEmail() === $email && password_verify( $password,$user->getPassword())) {
        //         $isInDatabase = true;
        //     }
        // }


        if (!$isInDatabase) {
            return $this->render('login', ['messages' => ['User not found!']]);
        }

        $data = Session::getInstance();
        $data->__set('user-email', $user->getEmail());
        $data->__set('user-name', $user->getName());
        $data->__set('user-surname', $user->getSurname());
        $data->__set('is-logged', true);


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
        return;

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

        $user = new User($email, password_hash($password, PASSWORD_BCRYPT), $name, $surname);
        self::$users[] = $user;



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