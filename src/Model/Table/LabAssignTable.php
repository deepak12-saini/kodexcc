<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class LabAssignTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // DB uses singular `lab_assign` (not `lab_assigns`). See config/schema/ensure_lab_assign_columns.sql
        $this->setTable('lab_assign');
        $this->belongsTo('LabFile', [
            'foreignKey' => 'lab_id',
        ]);
        $this->belongsTo('NappUser', [
            'foreignKey' => 'customer_id',
        ]);
    }
}
