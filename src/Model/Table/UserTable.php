<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class UserTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // CakePHP defaults this model to table `user` (singular). Admin accounts live in `users`.
        $this->setTable('users');
        $this->setPrimaryKey('id');
        $this->setDisplayField('username');
    }
}
