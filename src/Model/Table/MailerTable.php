<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class MailerTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('mailer');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');

        $this->addBehavior('Timestamp');

        // Old DB snapshots may not have `admin_id` yet; fall back to `user_id`
        // so admin list pages keep working until schema migration is applied.
        $ownerForeignKey = $this->getSchema()->hasColumn('admin_id') ? 'admin_id' : 'user_id';
        $this->belongsTo('User', [
            'foreignKey' => $ownerForeignKey,
            'className' => 'User',
        ]);
    }
}
