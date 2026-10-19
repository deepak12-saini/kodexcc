<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ErpSalesQuotationLinesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('erp_sales_quotation_lines');
        $this->setPrimaryKey('id');
    }
}
