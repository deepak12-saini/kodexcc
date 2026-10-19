<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpPurchaseOrdersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_purchase_orders');
        $this->setPrimaryKey('id');
        $this->setDisplayField('po_no');
    }
}
