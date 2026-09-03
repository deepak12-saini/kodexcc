<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class HrAssetsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('hr_assets');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
        $this->hasMany('HrAssetAssignments', ['foreignKey' => 'asset_id']);
    }
}
