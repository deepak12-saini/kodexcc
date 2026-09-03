<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrAuditLogsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_audit_logs');
        $this->setPrimaryKey('id');
        $this->belongsTo('HrUsers', [
            'foreignKey' => 'actor_user_id',
            'propertyName' => 'actor_user',
        ]);
        $this->belongsTo('HrEmployees', ['foreignKey' => 'employee_id']);
    }
}
