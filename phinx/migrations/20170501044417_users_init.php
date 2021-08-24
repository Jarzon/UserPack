<?php

use Phinx\Migration\AbstractMigration;

class UsersInit extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');
        $table
            ->addColumn('name', 'string', ['limit' => 50])
            ->addColumn('email', 'string', ['limit' => 80])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('status', 'integer', ['default' => '0'])
            ->addColumn('reset', 'string', ['limit' => 40, 'default' => ''])
            ->addColumn('updated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
