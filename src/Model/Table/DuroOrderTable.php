<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class DuroOrderTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('duro_order');
        $this->setPrimaryKey('id');

        $this->belongsTo('NappUser', [
            'foreignKey' => 'user_id',
            'className' => 'NappUser',
            'joinType' => 'LEFT',
        ]);

        $this->hasMany('OrderProduct', [
            'foreignKey' => 'order_id',
            'className' => 'OrderProduct',
            'dependent' => false,
        ]);
    }
}
