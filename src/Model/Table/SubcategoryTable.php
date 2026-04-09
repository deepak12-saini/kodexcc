<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class SubcategoryTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('subcategory');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Category', [
            'foreignKey' => 'category_id',
        ]);
    }
}
