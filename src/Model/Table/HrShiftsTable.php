<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrShiftsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_shifts');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
    }
}
