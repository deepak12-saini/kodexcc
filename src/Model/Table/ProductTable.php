<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ProductTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('product');
        $this->setPrimaryKey('id');

        $this->belongsTo('Category', [
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'joinType' => 'LEFT',
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always',
                ],
            ],
        ]);
    }
}
