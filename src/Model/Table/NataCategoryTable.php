<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class NataCategoryTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('nata_category');
        $this->hasMany('NataEvents', [
            'className' => 'NataEvent',
            'foreignKey' => 'cate_id',
        ]);
    }
}
