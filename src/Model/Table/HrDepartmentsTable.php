<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrDepartmentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_departments');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
        $this->hasMany('HrEmployees', ['foreignKey' => 'department_id']);
        $this->hasMany('HrDesignations', ['foreignKey' => 'department_id']);
    }
}
