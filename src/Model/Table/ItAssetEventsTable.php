<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItAssetEventsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_asset_events');
        $this->setPrimaryKey('id');
        $this->belongsTo('ItAssets', ['foreignKey' => 'asset_id']);
    }
}
