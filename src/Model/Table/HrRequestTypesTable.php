<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrRequestTypesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_request_types');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
        $this->hasMany('HrRequests', ['foreignKey' => 'request_type_id']);
    }
}
