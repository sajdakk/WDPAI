<?php

require_once __DIR__ . '/../../Database.php';

class Repository
{
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function startTransaction()
    {
        $this->database->connect()->beginTransaction();
    }

    public function commit()
    {
        $this->database->connect()->commit();
    }

    public function rollback()
    {
        $this->database->connect()->rollBack();
    }
}