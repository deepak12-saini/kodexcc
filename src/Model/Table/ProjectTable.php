<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ProjectTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('project');
        $this->setPrimaryKey('id');

        $this->belongsTo('Product', [
            'foreignKey' => 'product_id',
            'joinType' => 'LEFT',
        ]);
    }
}
