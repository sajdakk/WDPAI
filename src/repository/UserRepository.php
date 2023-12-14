<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/user/User.php';
require_once __DIR__ . '/../models/user/UserWriteRequest.php';

class UserRepository extends Repository
{
    public function getUsers(): array
    {


        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users;
        ');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($users as $user) {
            $result[] = new User(
                $user['id'],
                $user['email'],
                $user['password'],
                $user['name'],
                $user['surname'],
            );
        }

        return $result;
    }


    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User(
            $user['id'],
            $user['email'],
            $user['password'],
            $user['name'],
            $user['surname']
        );
    }

    public function addUser(UserWriteRequest $userWriteRequest)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (name, surname, email, password)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $userWriteRequest->getName(),
            $userWriteRequest->getSurname(),
            $userWriteRequest->getEmail(),
            $userWriteRequest->getPassword()
        ]);


    }
}