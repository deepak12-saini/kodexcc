<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrDocumentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_documents');
        $this->setPrimaryKey('id');
        $this->belongsTo('HrEmployees', ['foreignKey' => 'employee_id']);
    }
}
