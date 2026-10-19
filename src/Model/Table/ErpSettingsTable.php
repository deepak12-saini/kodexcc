<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpSettingsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_settings');
        $this->setPrimaryKey('id');
        $this->setDisplayField('setting_key');
    }
}
