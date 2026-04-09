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
    }
}
