<?php

use Phinx\Migration\AbstractMigration;

class UsersInit extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');
        $table
            ->addColumn('name', 'string', ['limit' => 40])
            ->addColumn('email', 'string', ['limit' => 50])
            ->addColumn('password', 'string', ['limit' => 128])
            ->addColumn('status', 'integer', ['default' => '0'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['name'], ['unique' => true])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}