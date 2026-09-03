<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrLeaveTypesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_leave_types');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
    }
}
