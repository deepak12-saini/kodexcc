<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class RewardProductTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('reward_product');
        $this->belongsTo('Product', [
            'foreignKey' => 'product_id',
            'className' => 'Product',
        ]);
    }
}
