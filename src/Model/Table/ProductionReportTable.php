<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ProductionReportTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('production_report');
        $this->belongsTo('NappUser', [
            'foreignKey' => 'user_id',
            'className' => 'NappUser',
        ]);
    }
}
