<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class OrderProductTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('order_product');
        $this->setPrimaryKey('id');

        $this->belongsTo('Product', [
            'foreignKey' => 'product_id',
            'className' => 'Product',
            'joinType' => 'LEFT',
        ]);
    }
}
