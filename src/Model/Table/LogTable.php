<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class LogTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('logs');

        $this->belongsTo('Client', [
            'foreignKey' => 'client_id',
        ]);
    }
}
