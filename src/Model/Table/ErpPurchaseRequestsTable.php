<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpPurchaseRequestsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_purchase_requests');
        $this->setPrimaryKey('id');
        $this->setDisplayField('request_no');
    }
}
