<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class NappUserTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('napp_user');

        $this->hasMany('LabAssign', [
            'foreignKey' => 'customer_id',
        ]);
    }
}
