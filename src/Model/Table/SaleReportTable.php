<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class SaleReportTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('sale_report');
    }
}
