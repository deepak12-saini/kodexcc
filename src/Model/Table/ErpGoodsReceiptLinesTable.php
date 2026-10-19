<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpGoodsReceiptLinesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_goods_receipt_lines');
        $this->setPrimaryKey('id');
    }
}
