<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrRequestsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_requests');
        $this->setPrimaryKey('id');
        $this->setDisplayField('title');
        $this->belongsTo('HrEmployees', ['foreignKey' => 'employee_id']);
        $this->belongsTo('HrRequestTypes', ['foreignKey' => 'request_type_id']);
        $this->belongsTo('HrAssets', [
            'foreignKey' => 'linked_asset_id',
            'propertyName' => 'linked_asset',
        ]);
    }
}
