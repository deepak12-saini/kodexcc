<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItVendorsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_vendors');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
        $this->hasMany('ItAssets', ['foreignKey' => 'vendor_id']);
        $this->hasMany('ItRepairs', ['foreignKey' => 'vendor_id']);
        $this->hasMany('ItPurchaseRequests', ['foreignKey' => 'vendor_id']);
        $this->hasMany('ItMaintenance', ['foreignKey' => 'vendor_id']);
    }
}
