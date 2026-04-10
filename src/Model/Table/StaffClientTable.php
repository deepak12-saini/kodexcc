<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class StaffClientTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('staff_client');

        $this->belongsTo('Client', [
            'foreignKey' => 'client_id',
        ]);
        $this->belongsTo('NappUser', [
            'foreignKey' => 'staff_id',
        ]);
    }
}
