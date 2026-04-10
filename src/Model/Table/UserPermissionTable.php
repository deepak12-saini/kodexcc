<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class UserPermissionTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('user_permissions');

        $this->belongsTo('NappUser', [
            'foreignKey' => 'user_id',
        ]);
    }
}
