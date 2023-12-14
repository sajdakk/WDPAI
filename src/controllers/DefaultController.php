<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/book/Book.php';
require_once __DIR__ . '/../models/author/Author.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../repository/BookRepository.php';


class DefaultController extends AppController
{

    private $bookRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookRepository = new BookRepository();
    }


    function login()
    {
        if ($this->isGet()) {
            return $this->render('login');
        }

        if ($this->isPost()) {
            //TODO check if user cookie exist
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            return;
        }
    }

    public function dashboard()
    {
        $title = "What can I find for you?";

        $books = $this->bookRepository->getBooks();
       

        $data = Session::getInstance();

        $this->render(
            'dashboard',
            [
                'title' => $title,
                'books' => $books,
                'isLogged' => $data->__get('is-logged'),
            ],
        );
    }
    public function top()
    {

        $data = Session::getInstance();

        $this->render(
            'top',
            [
                'isLogged' => $data->__get('is-logged'),
            ],
        );
    }

    public function favorites()
    {
        $data = Session::getInstance();

        $this->render(
            'favorites',
            [
                'isLogged' => $data->__get('is-logged'),
            ],
        );
    }

    public function registration()
    {
        $this->render('registration');
    }

    public function profile()
    {
        $data = Session::getInstance();

        $this->render(
            'profile',
            [
                'isLogged' => $data->__get('is-logged'),
            ],
        );
    }

    public function details()
    {
        $data = Session::getInstance();

        $this->render(
            'details',
            [
                'isLogged' => $data->__get('is-logged'),
            ],
        );

    }
}