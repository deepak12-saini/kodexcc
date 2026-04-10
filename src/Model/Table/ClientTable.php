<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ClientTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('client');

        $this->belongsTo('ClientType', [
            'foreignKey' => 'category_id',
        ]);
        $this->hasMany('StaffClients', [
            'className' => 'StaffClient',
            'foreignKey' => 'client_id',
        ]);
    }
}
