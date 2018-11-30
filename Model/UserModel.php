<?php
namespace UserPack\Model;

class UserModel extends \Prim\Model
{
    public function exists(string $email, string $name) : bool
    {
        $query = $this->db->prepare("
            SELECT id
            FROM users
            WHERE email = ? OR name = ?
            LIMIT 1");

        $query->execute([$email, $name]);

        return ($query->fetch())? true: false;
    }

    public function canResetPassword(string $email, string $token)
    {
        if($user = $this->getUserResetByEmail($email)) {
            if($user->reset === $token) {
                return true;
            }
        }

        return false;
    }

    public function getUserResetByEmail(string $email)
    {
        $query = $this->db->prepare("
            SELECT id, reset
            FROM users
            WHERE email = ?
            LIMIT 1");

        $query->execute([$email]);

        return $query->fetch();
    }

    public function getUserByName(string $name)
    {
        $query = $this->db->prepare("
            SELECT id
            FROM users
            WHERE name = ?
            LIMIT 1");

        $query->execute([$name]);

        return $this->getUser($query->fetch()->id);
    }

    public function getUserByEmail(string $email)
    {
        $query = $this->db->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1");

        $query->execute([$email]);

        if(!$user = $query->fetch()) {
            return false;
        }

        return $this->getUser($user->id);
    }

    public function signUp(array $params)
    {
        $this->insert('users', $params);

        return $this->signIn($params['name']);
    }

    public function signIn(string $name)
    {
        $query = $this->db->prepare("
            SELECT id, name, email, password, status
            FROM users
            WHERE name = ?");

        $query->execute($name);

        return $query->fetch()->id;
    }


    public function getAllUsers()
    {
        $query = $this->db->prepare("
            SELECT id, name
            FROM users");
        $query->execute();

        return $query->fetchAll();
    }

    public function deleteUser(int $user_id)
    {
        $query = $this->db->prepare("DELETE FROM users WHERE id = :user_id");
        $parameters = array(':user_id' => user_id);

        $query->execute($parameters);
    }

    public function getUser(int $user_id)
    {
        $query = $this->db->prepare("SELECT id, name, email FROM users WHERE id = :user_id LIMIT 1");
        $parameters = array(':user_id' => $user_id);

        $query->execute($parameters);

        return $query->fetch();
    }

    public function updateUser(string $name, int $user_id)
    {
        $this->update('users', ['name' => $name], 'id = ?', [$user_id]);
    }

    public function getAmountOfUsers()
    {
        $query = $this->db->prepare("SELECT COUNT(id) AS amount_of_users FROM users");
        $query->execute();

        return $query->fetch()->amount_of_users;
    }

    public function getUserSettings(int $user_id)
    {
        $query = $this->db->prepare("SELECT email FROM users WHERE id = ? LIMIT 1");

        $query->execute([$user_id]);

        return $query->fetch();
    }

    public function saveUserSettings(array $values, int $user_id)
    {
        $this->update('users', $values, 'id = ?', [$user_id]);
    }
}