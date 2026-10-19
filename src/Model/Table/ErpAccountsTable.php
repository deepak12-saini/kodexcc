<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpAccountsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_accounts');
        $this->setPrimaryKey('id');
    }
}
