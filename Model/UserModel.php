<?php
namespace UserPack\Model;

use Jarzon\QueryBuilder\Builder as QB;

use UserPack\Entity\UserEntity;

class UserModel extends \Prim\Model
{
    private $user;

    public function __construct($db, array $options = [], $user)
    {
        parent::__construct($db, $options);

        $this->user = $user;
    }

    public function getUserByEmail(string $email)
    {
        $u = new UserEntity();

        $query = QB::select($u)
            ->columns($u->id, $u->name, $u->email, $u->password, $u->status, $u->reset)
            ->where($u->email, '=', $email)
            ->limit(1);

        return $query->fetch();
    }

    public function getUser(int $user_id)
    {
        $u = new UserEntity();

        $query = QB::select($u)
            ->columns()
            ->where($u->id, '=', $user_id)
            ->limit(1);

        return $query->fetch();
    }

    public function getUserSettings(?int $user_id = null)
    {
        $u = new UserEntity();

        $query = QB::select($u)
            ->columns($u->email)
            ->where($u->id, '=', $user_id);

        return $query->fetch();
    }

    public function exists(string $email) : bool
    {
        return !empty($this->getUserByEmail($email))? true: false;
    }

    public function canResetPassword(string $email, string $token): bool
    {
        if($user = $this->getUserByEmail($email)) {
            if($user->reset === $token) {
                return true;
            }
        }

        return false;
    }

    public function signUp(array $post)
    {
        $u = new UserEntity();

        $query = QB::insert($u)
            ->columns($post);

        return $query->exec();
    }

    public function signIn(string $email): object
    {
        return $this->getUserByEmail($email);
    }

    public function getAllUsers()
    {
        $u = new UserEntity();

        $query = QB::select($u)
            ->columns($u->id, $u->name)
            ->orderBy($u->id);

        return $query->fetchAll();
    }

    public function deleteUser(int $user_id)
    {
        return $this->updateUser(['status' => -1]);
    }

    public function updateUser(array $post, ?int $user_id = null)
    {
        $u = new UserEntity();

        $query = QB::update($u)
            ->columns($post)
            ->where($u->id, '=', $user_id ?? $this->user->id);

        return $query->exec();
    }

    public function setConnectionTime(?int $user_id = null)
    {
        $u = new UserEntity();

        $query = QB::update($u)
            ->setRaw($u->updated, 'NOW()')
            ->where($u->id, '=', $user_id ?? $this->user->id);

        return $query->exec();
    }

    public function getNumberOfUsers()
    {
        $u = new UserEntity();

        $query = QB::select($u)
            ->columns($u->id->count());

        return $query->fetchColumn();
    }
}
