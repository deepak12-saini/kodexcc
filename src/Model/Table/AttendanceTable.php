<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class AttendanceTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('attendances');

        $this->belongsTo('NappUser', [
            'foreignKey' => 'staff_id',
        ]);
    }
}
