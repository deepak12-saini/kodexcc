<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ClientTypeTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('client_type');

        $this->hasMany('Clients', [
            'className' => 'Client',
            'foreignKey' => 'category_id',
        ]);
    }
}
