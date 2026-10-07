<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItTicketUpdatesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_ticket_updates');
        $this->setPrimaryKey('id');
        $this->belongsTo('ItTickets', ['foreignKey' => 'ticket_id']);
        $this->belongsTo('HrUsers', ['foreignKey' => 'actor_user_id']);
    }
}
