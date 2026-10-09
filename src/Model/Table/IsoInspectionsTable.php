<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class IsoInspectionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('iso_inspections');
        $this->setPrimaryKey('id');
        $this->setDisplayField('id');
    }
}
