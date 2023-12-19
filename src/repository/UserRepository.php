<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/user/User.php';
require_once __DIR__ . '/../models/user/UserWriteRequest.php';

class UserRepository extends Repository
{

    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        u.*,
        r.name AS role_name
    FROM
        users u
    JOIN
        roles r ON u.role_id = r.id
    WHERE
        u.email = :email;
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
            $user['surname'],
            $user['avatar'],
            $user['role_name']
        );
    }

    public function getUsers(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        u.*,
        r.name AS role_name
    FROM
        users u
    JOIN
        roles r ON u.role_id = r.id
        ');
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach ($result as $user) {
            $users[] = new User(
                $user['id'],
                $user['email'],
                $user['password'],
                $user['name'],
                $user['surname'],
                $user['avatar'],
                $user['role_name']
            );
        }

        return $users;
    }
    public function getUserWithId(string $id): ?User
    {
        $stmt = $this->database->connect()->prepare('
        SELECT
        u.*,
        r.name AS role_name
    FROM
        users u
    JOIN
        roles r ON u.role_id = r.id
    WHERE
        u.id = :id;
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
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
            $user['surname'],
            $user['avatar'],
            $user['role_name']
        );
    }

    public function addUser(UserWriteRequest $userWriteRequest)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (name, surname, email, password, role_id )
            VALUES (?, ?, ?, ?, 2)
        ');

        $stmt->execute([
            $userWriteRequest->getName(),
            $userWriteRequest->getSurname(),
            $userWriteRequest->getEmail(),
            $userWriteRequest->getPassword()
        ]);
    }

    public function removeUserWithId(int $userId): void
    {
        $stmt = $this->database->connect()->prepare('
    SELECT remove_user_and_reviews(:user_id)
    ');

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateAvatarWithUserId($avatar, $id)
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE users SET avatar = :avatar WHERE id = :id
        ');

        $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
    }
    public function addAdminToUserId($id)
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE users SET role_id = 3 WHERE id = :id
        ');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function removeAdminToUserId($id)
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE users SET role_id = 2 WHERE id = :id
        ');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}