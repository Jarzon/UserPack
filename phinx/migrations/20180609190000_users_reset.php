<?php

use Phinx\Migration\AbstractMigration;

class UsersReset extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');
        $table
            ->addColumn('reset', 'string', ['limit' => 40, 'after' => 'status'])
            ->update();
    }
}