<?php

class Database {

    private $connection;

    public function __construct($dsn, $userName, $password) {
        $this->connection = new PDO($dsn, $userName, $password);
    }

    public function insertOrUpdateUser($userId, $userName, $token) {
        $statement = $this->connection->prepare('select * from users where user_id = :user ');
        $statement->bindValue(':user', $userId, PDO::PARAM_INT);
        $statement->execute();
        //insert
        if ($statement->rowCount() == 0) {
            $this->insertUser($userId, $userName, $token);
        }
        // update
        else {
            $this->updateUser($userId, $token);
        }
    }

    public function insertUser($userId, $userName, $token) {
        $statement = $this->connection->prepare('INSERT INTO users(user_id, user_name, token, is_active)'
                . ' VALUES (:user_id,:user_name,:token,"true")');
        //$statement->execute(["user_id" => $userId, "user_name" => $userName, "token" => $token]);
        $statement->execute(array("user_id" => $userId, "user_name" => $userName, "token" => $token));
    }

    public function updateUser($userId, $token) {
        $statement = $this->connection->prepare('UPDATE users SET token=:token where user_id=:user_id');
        $statement->execute(array("user_id" => $userId, "token" => $token));
    }

    public function deauthUser($userId) {
        $statement = $this->connection->prepare('UPDATE users SET is_active="false" where user_id=:user_id');
        $statement->execute(array("user_id" => $userId));
    }

}
