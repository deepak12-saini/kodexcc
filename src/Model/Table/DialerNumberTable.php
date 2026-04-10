<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Dialer queue rows keyed by client (legacy feature).
 */
class DialerNumberTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('dialer_number');

        $this->belongsTo('Client', [
            'foreignKey' => 'client_id',
        ]);
    }
}
