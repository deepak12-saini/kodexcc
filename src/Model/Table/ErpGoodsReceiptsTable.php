<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpGoodsReceiptsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_goods_receipts');
        $this->setPrimaryKey('id');
        $this->setDisplayField('grn_no');
    }
}
