<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItPurchaseRequestsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_purchase_requests');
        $this->setPrimaryKey('id');
        $this->setDisplayField('request_no');
        $this->belongsTo('Requesters', [
            'className' => 'HrEmployees',
            'foreignKey' => 'requested_by',
            'propertyName' => 'requester',
        ]);
        $this->belongsTo('HrDepartments', ['foreignKey' => 'department_id']);
        $this->belongsTo('ItVendors', ['foreignKey' => 'vendor_id']);
        $this->belongsTo('Approvers', [
            'className' => 'HrUsers',
            'foreignKey' => 'approved_by',
            'propertyName' => 'approver',
        ]);
        $this->belongsTo('CreatedAssets', [
            'className' => 'ItAssets',
            'foreignKey' => 'asset_id',
            'propertyName' => 'created_asset',
        ]);
        $this->hasMany('ItAssets', ['foreignKey' => 'purchase_request_id']);
    }
}
