<?php declare(strict_types=1);

namespace UserPack\Entity;

use Jarzon\QueryBuilder\Entity\EntityBase;

class UserEntity extends EntityBase
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $status;
    public $reset;
    public $updated;
    public $created;

    public function __construct($alias = '')
    {
        parent::__construct($alias);

        $this->table('users');
        $this->id = $this->number('id');
        $this->name = $this->text('name');
        $this->email = $this->text('email');
        $this->password = $this->number('password');
        $this->status = $this->number('status');
        $this->reset = $this->number('reset');
        $this->updated = $this->date('updated');
        $this->created = $this->date('created');
    }
}
