<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItAssetsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_assets');
        $this->setPrimaryKey('id');
        $this->setDisplayField('asset_code');
        $this->belongsTo('ItVendors', ['foreignKey' => 'vendor_id']);
        $this->belongsTo('ItPurchaseRequests', ['foreignKey' => 'purchase_request_id']);
        $this->hasMany('ItAssetEvents', ['foreignKey' => 'asset_id']);
        $this->hasMany('ItAssetAssignments', ['foreignKey' => 'asset_id']);
        $this->hasMany('ItTickets', ['foreignKey' => 'asset_id']);
        $this->hasMany('ItRepairs', ['foreignKey' => 'asset_id']);
        $this->hasMany('ItMaintenance', ['foreignKey' => 'asset_id']);
    }
}
