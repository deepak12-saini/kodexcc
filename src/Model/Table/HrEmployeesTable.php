<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrEmployeesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_employees');
        $this->setPrimaryKey('id');
        $this->setDisplayField('full_name');
        $this->belongsTo('HrDepartments', ['foreignKey' => 'department_id']);
        $this->belongsTo('HrDesignations', ['foreignKey' => 'designation_id']);
        $this->belongsTo('HrShifts', ['foreignKey' => 'shift_id']);
        $this->belongsTo('Managers', [
            'className' => 'HrEmployees',
            'foreignKey' => 'manager_id',
            'propertyName' => 'manager',
        ]);
        $this->hasOne('HrUsers', ['foreignKey' => 'employee_id']);
        $this->hasMany('HrAttendances', ['foreignKey' => 'employee_id']);
        $this->hasMany('HrLeaveRequests', ['foreignKey' => 'employee_id']);
        $this->hasMany('HrDocuments', ['foreignKey' => 'employee_id']);
        $this->hasMany('HrAssetAssignments', ['foreignKey' => 'employee_id']);
    }
}
