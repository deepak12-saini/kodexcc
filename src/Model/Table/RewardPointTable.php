<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class RewardPointTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('reward_point');
        $this->setPrimaryKey('id');
    }
}
