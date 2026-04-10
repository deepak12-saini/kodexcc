<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class LoginHistoryTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        // Legacy login rows use role, logintime, logouttime — they live on `login_histories`,
        // not the wide generic `login_history` table from init_schema.sql.
        $this->setTable('login_histories');

        $alias = $this->getAlias();
        // Same `user_id` points at `users.id` for Admin rows and `napp_user.id` for others.
        $this->belongsTo('User', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
            'conditions' => [$alias . '.role' => 'Admin'],
        ]);
        $this->belongsTo('NappUser', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
            'conditions' => [$alias . '.role !=' => 'Admin'],
        ]);
    }
}
