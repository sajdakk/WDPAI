<?php

require_once 'app_controller.php';

class DefaultController extends AppController{

    function login() {
        $this -> render('login');
    }

    public function dashboard() {
        $this -> render('dashboard');
    }
    public function top() {
        $this -> render('top');
    }

    public function favorites() {
        $this -> render('favorites');
    }

    public function registration() {
        $this -> render('registration');
    }

    public function profile() {
        $this -> render('profile');
    }

    public function create() {
        $this -> render('create');
    }

    public function details() {
        $this -> render('details');
    }
}