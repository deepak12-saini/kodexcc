<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class CategoryTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('category');
        $this->setPrimaryKey('id');

        $this->hasMany('Product', [
            'className' => 'Product',
            'foreignKey' => 'category_id',
        ]);

        $this->hasMany('Subcategory', [
            'foreignKey' => 'category_id',
        ]);
    }
}
