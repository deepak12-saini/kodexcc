<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrLeaveRequestsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_leave_requests');
        $this->setPrimaryKey('id');
        $this->belongsTo('HrEmployees', ['foreignKey' => 'employee_id']);
        $this->belongsTo('HrLeaveTypes', ['foreignKey' => 'leave_type_id']);
    }
}
