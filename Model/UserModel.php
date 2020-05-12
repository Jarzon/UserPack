<?php
namespace UserPack\Model;

use UserPack\Service\User;

class UserModel extends \Prim\Model
{
    private User $user;

    public function __construct($db, array $options = [], $user)
    {
        parent::__construct($db, $options);

        $this->user = $user;
    }

    public function exists(string $email) : bool
    {
        $query = $this->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1");

        $query->execute([$email]);

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
        $query = $this->prepare("
            SELECT id, reset
            FROM users
            WHERE email = ?
            LIMIT 1");

        $query->execute([$email]);

        return $query->fetch();
    }

    public function getUserByEmail(string $email)
    {
        $query = $this->prepare("
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
        return $this->insert('users', $params);
    }

    public function signIn(string $email)
    {
        $query = $this->prepare("
            SELECT id, name, email, password, status
            FROM users
            WHERE email = ?");

        $query->execute([$email]);

        return $query->fetch()->id;
    }


    public function getAllUsers()
    {
        $query = $this->prepare("
            SELECT id, name
            FROM users
            ORDER BY id DESC");
        $query->execute();

        return $query->fetchAll();
    }

    public function deleteUser(int $user_id)
    {
        $query = $this->prepare("DELETE FROM users WHERE id = ?");

        $query->execute([$user_id]);
    }

    public function getUser(int $user_id)
    {
        $query = $this->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");

        $query->execute([$user_id]);

        return $query->fetch();
    }

    public function updateUser(array $post, ?int $user_id)
    {
        $this->update('users', $post, 'id = ?', [$user_id ?? $this->user->id]);
    }

    public function getAmountOfUsers()
    {
        $query = $this->prepare("SELECT COUNT(id) AS amount_of_users FROM users");
        $query->execute();

        return $query->fetch()->amount_of_users;
    }

    public function getUserSettings()
    {
        $query = $this->prepare("SELECT email FROM users WHERE id = ? LIMIT 1");

        $query->execute([$this->user->id]);

        return $query->fetch();
    }
}
