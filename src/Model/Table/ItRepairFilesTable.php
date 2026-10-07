<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItRepairFilesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_repair_files');
        $this->setPrimaryKey('id');
        $this->belongsTo('ItRepairs', ['foreignKey' => 'repair_id']);
    }
}
