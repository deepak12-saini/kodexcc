<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class IsoTrainingTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('iso_training');
        $this->setPrimaryKey('id');
        $this->setDisplayField('person_name');
    }
}
