<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItRepairsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_repairs');
        $this->setPrimaryKey('id');
        $this->setDisplayField('repair_no');
        $this->belongsTo('ItAssets', ['foreignKey' => 'asset_id']);
        $this->belongsTo('ItTickets', ['foreignKey' => 'ticket_id']);
        $this->belongsTo('ItVendors', ['foreignKey' => 'vendor_id']);
        $this->hasMany('ItRepairFiles', ['foreignKey' => 'repair_id']);
    }
}
