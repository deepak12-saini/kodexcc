<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrDesignationsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_designations');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
        $this->belongsTo('HrDepartments', ['foreignKey' => 'department_id']);
    }
}
