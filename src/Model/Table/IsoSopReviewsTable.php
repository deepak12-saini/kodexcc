<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class IsoSopReviewsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('iso_sop_reviews');
        $this->setPrimaryKey('id');
        $this->setDisplayField('employee_name');
    }
}
