<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpQuotationsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_quotations');
        $this->setPrimaryKey('id');
        $this->setDisplayField('quotation_no');
    }
}
