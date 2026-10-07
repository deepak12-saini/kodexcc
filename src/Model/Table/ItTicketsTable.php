<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class ItTicketsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('it_tickets');
        $this->setPrimaryKey('id');
        $this->setDisplayField('ticket_no');
        $this->belongsTo('HrEmployees', ['foreignKey' => 'employee_id']);
        $this->belongsTo('ItAssets', ['foreignKey' => 'asset_id']);
        $this->belongsTo('AssignedUsers', [
            'className' => 'HrUsers',
            'foreignKey' => 'assigned_user_id',
            'propertyName' => 'assigned_user',
        ]);
        $this->hasMany('ItTicketUpdates', ['foreignKey' => 'ticket_id']);
        $this->hasMany('ItRepairs', ['foreignKey' => 'ticket_id']);
    }
}
