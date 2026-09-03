<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrAssetAssignmentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_asset_assignments');
        $this->setPrimaryKey('id');
        $this->belongsTo('HrAssets', ['foreignKey' => 'asset_id']);
        $this->belongsTo('HrEmployees', ['foreignKey' => 'employee_id']);
    }
}
