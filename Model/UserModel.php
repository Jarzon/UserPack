<?php
namespace Jarzon\UserPack\Model;

class UserModel extends \Prim\Model
{
    public function exists(array $params) : bool
    {
        $query = $this->db->prepare("
            SELECT id
            FROM users
            WHERE name = ? OR password = ?
            LIMIT 0, 1");

        $query->execute($params);

        return ($query->fetch())? true: false;
    }

    public function signUp($params)
    {
        $query = $this->db->prepare("INSERT INTO users(email, name, password) VALUES (?, ?, ?)");

        return $query->execute($params);
    }

    public function signIn($params)
    {
        $query = $this->db->prepare("
            SELECT id, name, email, password, status
            FROM users
            WHERE name = ?");

        $query->execute($params);

        return $query->fetch();
    }


    public function getAllUsers()
    {
        $query = $this->db->prepare("
            SELECT id, name
            FROM users");
        $query->execute();

        return $query->fetchAll();
    }

    public function addUser(string $name)
    {
        $query = $this->db->prepare("INSERT INTO users (name) VALUES (:name)");
        $parameters = array(':name' => $name);

        $query->execute($parameters);
    }

    public function deleteUser(int $user_id)
    {
        $query = $this->db->prepare("DELETE FROM users WHERE id = :user_id");
        $parameters = array(':user_id' => user_id);

        $query->execute($parameters);
    }

    public function getUser(int $user_id)
    {
        $query = $this->db->prepare("SELECT id, name FROM users WHERE id = :user_id LIMIT 1");
        $parameters = array(':user_id' => $user_id);

        $query->execute($parameters);

        return $query->fetch();
    }

    public function updateUser(string $name, int $user_id)
    {
        $query = $this->db->prepare("UPDATE users SET name = :name WHERE id = :user_id");
        $parameters = array(':name' => $name, ':user_id' => $user_id);

        $query->execute($parameters);
    }

    public function getAmountOfUsers()
    {
        $query = $this->db->prepare("SELECT COUNT(id) AS amount_of_users FROM users");
        $query->execute();

        return $query->fetch()->amount_of_users;
    }
}