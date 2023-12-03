<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Author.php';
require_once __DIR__ . '/../models/Session.php';

class DefaultController extends AppController
{

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

        $book1 = new Book(
            "The Catcher in the Rye",
            "Fiction",
            "English",
            "New York",
            224,
            "https://www.southernliving.com/thmb/WYR3KLFcxNQ1MuZNVTzDe0ku33A=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/gettyimages-183822187-1-709a5ded972a426a9e214eba1f81c8a4.jpg",
            "A classic novel about the experiences of a teenage boy in New York City."
        );

        $book2 = new Book(
            "To Kill a Mockingbird",
            "Fiction",
            "English",
            "New York",
            281,
            "https://www.southernliving.com/thmb/WYR3KLFcxNQ1MuZNVTzDe0ku33A=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/gettyimages-183822187-1-709a5ded972a426a9e214eba1f81c8a4.jpg",
            "A powerful portrayal of racial injustice and moral growth in the American South."
        );

        $book3 = new Book(
            "1984",
            "Fiction",
            "English",
            "London",
            328,
            "https://www.southernliving.com/thmb/WYR3KLFcxNQ1MuZNVTzDe0ku33A=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/gettyimages-183822187-1-709a5ded972a426a9e214eba1f81c8a4.jpg",
            "A dystopian novel exploring themes of totalitarianism, surveillance, and individualism."
        );

        $book4 = new Book(
            "The Great Gatsby",
            "Fiction",
            "English",
            "New York",
            180,
            "https://www.southernliving.com/thmb/WYR3KLFcxNQ1MuZNVTzDe0ku33A=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/gettyimages-183822187-1-709a5ded972a426a9e214eba1f81c8a4.jpg",
            "A story of the Jazz Age and the American Dream, set against the backdrop of the Roaring Twenties."
        );

        $book5 = new Book(
            "One Hundred Years of Solitude",
            "Fiction",
            "Spanish",
            "Bogotá",
            417,
            "https://www.southernliving.com/thmb/WYR3KLFcxNQ1MuZNVTzDe0ku33A=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/gettyimages-183822187-1-709a5ded972a426a9e214eba1f81c8a4.jpg",
            "A landmark novel in magical realism, telling the multi-generational story of the Buendía family."
        );

        $books = [
            $book1,
            $book2,
            $book3,
            $book4,
            $book5,
        ];

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

    public function create()
    {
        $authors = array(
            new Author('John', 'Doe'),
            new Author('Jane', 'Smith'),
            new Author('Bob', 'Johnson'),
            new Author('Alice', 'Williams'),
            new Author('Charlie', 'Brown'),
            new Author('Eva', 'Miller'),

        );

        $data = Session::getInstance();

        $this->render(
            'create',
            [
                'isLogged' => $data->__get('is-logged'),
                'authors' => $authors,
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