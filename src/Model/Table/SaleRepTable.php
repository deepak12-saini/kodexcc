<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class SaleRepTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('sale_rep');

        $this->belongsTo('SaleQuestion', [
            'foreignKey' => 'sales_question_id',
        ]);
        $this->belongsTo('NappUser', [
            'foreignKey' => 'staff_id',
        ]);
    }
}
