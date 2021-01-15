<?php declare(strict_types=1);
namespace UserPack\Model;

use Jarzon\QueryBuilder\Builder as QB;

use UserPack\Entity\UserEntity;

class UserModel extends \Prim\Model
{
    private $user;
    private $userEntity;

    public function __construct($db, array $options, $user, $userEntity)
    {
        parent::__construct($db, $options);

        $this->user = $user;
        $this->userEntity = $userEntity;
    }

    public function getUserByEmail(string $email): ?object
    {
        $u = $this->userEntity;

        $query = QB::select($u)
            ->columns($u->id, $u->name, $u->email, $u->password, $u->status, $u->reset)
            ->where($u->email, '=', $email)
            ->limit(1);

        $data = $query->fetch();

        return $data ?: null;
    }

    public function getUser(int $user_id): object|false
    {
        $u = $this->userEntity;

        $query = QB::select($u)
            ->columns('*')
            ->where($u->id, '=', $user_id)
            ->limit(1);

        return $query->fetch();
    }

    public function getUserSettings(?int $user_id = null): object|false
    {
        $u = $this->userEntity;

        $query = QB::select($u)
            ->columns($u->email)
            ->where($u->id, '=', $user_id);

        return $query->fetch();
    }

    public function exists(string $email): bool
    {
        return !empty($this->getUserByEmail($email));
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

    public function signUp(array $post): object|false
    {
        $u = $this->userEntity;

        $query = QB::insert($u)
            ->columns($post);

        return $query->exec();
    }

    public function getAllUsers(): array|false
    {
        $u = $this->userEntity;

        $query = QB::select($u)
            ->columns($u->id, $u->name)
            ->orderBy($u->id);

        return $query->fetchAll();
    }

    public function deleteUser(int $user_id): int|false
    {
        return $this->updateUser(['status' => -1]);
    }

    public function updateUser(array $post, ?int $user_id = null): int|false
    {
        $u = $this->userEntity;

        $query = QB::update($u)
            ->columns($post)
            ->where($u->id, '=', $user_id ?? $this->user->id);

        return $query->exec();
    }

    public function setConnectionTime(?int $user_id = null): int|false
    {
        $u = $this->userEntity;

        $query = QB::update($u)
            ->setRaw($u->updated, 'NOW()')
            ->where($u->id, '=', $user_id ?? $this->user->id);

        return $query->exec();
    }

    public function getNumberOfUsers(): int|false
    {
        $u = $this->userEntity;

        $query = QB::select($u)
            ->columns($u->id->count());

        return $query->fetchColumn();
    }
}
