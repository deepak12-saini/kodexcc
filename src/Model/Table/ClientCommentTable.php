<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ClientCommentTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('client_comment');

        $this->belongsTo('Client', [
            'foreignKey' => 'client_id',
        ]);
        $this->belongsTo('User', [
            'foreignKey' => 'admin_id',
        ]);
        $this->belongsTo('NappUser', [
            'foreignKey' => 'emp_id',
        ]);
    }
}
