<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class TicketsController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'IT Tickets');
        $status = (string)$this->request->getQuery('status');
        $priority = (string)$this->request->getQuery('priority');
        $q = trim((string)$this->request->getQuery('q'));
        $query = $this->fetchTable('ItTickets')->find()->contain(['HrEmployees', 'ItAssets', 'AssignedUsers' => ['HrEmployees']]);
        if ($status === 'active') {
            $query->where(['ItTickets.status IN' => ['open', 'assigned', 'in_progress', 'waiting']]);
        } elseif (in_array($status, self::TICKET_STATUSES, true)) {
            $query->where(['ItTickets.status' => $status]);
        }
        if (in_array($priority, self::TICKET_PRIORITIES, true)) {
            $query->where(['ItTickets.priority' => $priority]);
        }
        if ($q !== '') {
            $query->leftJoinWith('HrEmployees')->where([
                'OR' => [
                    'ItTickets.ticket_no LIKE' => '%' . $q . '%',
                    'ItTickets.problem LIKE' => '%' . $q . '%',
                    'HrEmployees.full_name LIKE' => '%' . $q . '%',
                ],
            ])->distinct(['ItTickets.id']);
        }
        $this->hrPaginate($query, [
            'order' => ['ItTickets.id' => 'DESC'],
            'sortableFields' => ['ItTickets.ticket_no', 'ItTickets.status', 'ItTickets.priority', 'ItTickets.created'],
        ]);
        $this->set(compact('status', 'priority', 'q'));
        $this->set('ticketStatuses', self::TICKET_STATUSES);
        $this->set('ticketPriorities', self::TICKET_PRIORITIES);
    }

    public function add()
    {
        $this->set('pageTitle', 'New IT Ticket');
        $table = $this->fetchTable('ItTickets');
        $entity = $table->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), [], []);
            $status = in_array($data['status'] ?? '', self::TICKET_STATUSES, true) ? $data['status'] : 'open';
            if (!empty($data['assigned_user_id']) && $status === 'open') {
                $status = 'assigned';
            }
            $entity = $table->patchEntity($entity, [
                'ticket_no' => $this->nextCode('ItTickets', 'ticket_no', 'IT-TKT-'),
                'employee_id' => $data['employee_id'] ?? null,
                'asset_id' => $data['asset_id'] ?? null,
                'category' => isset(self::TICKET_CATEGORIES[$data['category'] ?? '']) ? $data['category'] : 'other',
                'priority' => in_array($data['priority'] ?? '', self::TICKET_PRIORITIES, true) ? $data['priority'] : 'medium',
                'problem' => trim((string)($data['problem'] ?? '')),
                'assigned_user_id' => $data['assigned_user_id'] ?? null,
                'status' => $status,
                'created' => $this->now(),
                'modified' => $this->now(),
            ]);
            if ($entity->problem === '') {
                $this->Flash->error('Describe the problem.');
            } elseif ($table->save($entity)) {
                $this->addUpdate((int)$entity->id, (string)$entity->status, 'Ticket opened.');
                $this->auditLog('it_ticket_create', 'it_ticket', 'Opened ' . $entity->ticket_no, (int)$entity->id, $entity->employee_id ? (int)$entity->employee_id : null);
                $this->Flash->success('Ticket ' . $entity->ticket_no . ' opened.');

                return $this->redirect(['action' => 'view', $entity->id]);
            } else {
                $this->Flash->error('Could not save the ticket.');
            }
        }
        $this->setLookups($entity);
        $this->render('form');
    }

    public function view($id = null)
    {
        $ticket = $this->fetchTable('ItTickets')->get($id, contain: [
            'HrEmployees',
            'ItAssets',
            'AssignedUsers' => ['HrEmployees'],
            'ItTicketUpdates' => ['HrUsers'],
            'ItRepairs',
        ]);
        $this->set('pageTitle', $ticket->ticket_no);
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $status = in_array($data['status'] ?? '', self::TICKET_STATUSES, true) ? $data['status'] : $ticket->status;
            $ticket->status = $status;
            $ticket->priority = in_array($data['priority'] ?? '', self::TICKET_PRIORITIES, true) ? $data['priority'] : $ticket->priority;
            $ticket->assigned_user_id = ($data['assigned_user_id'] ?? '') !== '' ? (int)$data['assigned_user_id'] : null;
            $ticket->resolution = trim((string)($data['resolution'] ?? '')) ?: $ticket->resolution;
            if (in_array($status, ['resolved', 'closed'], true) && empty($ticket->resolved_at)) {
                $ticket->resolved_at = $this->now();
            }
            if ($status === 'closed') {
                $ticket->closed_at = $ticket->closed_at ?: $this->now();
            }
            $ticket->modified = $this->now();
            if ($this->fetchTable('ItTickets')->save($ticket)) {
                $note = trim((string)($data['note'] ?? ''));
                $this->addUpdate((int)$ticket->id, $status, $note !== '' ? $note : 'Status set to ' . str_replace('_', ' ', $status));
                $this->auditLog('it_ticket_update', 'it_ticket', $ticket->ticket_no . ' → ' . $status, (int)$ticket->id);
                $this->Flash->success('Ticket updated.');

                return $this->redirect(['action' => 'view', $ticket->id]);
            }
            $this->Flash->error('Could not update the ticket.');
        }
        $itUsers = $this->itUserOptions();
        $ticketStatuses = self::TICKET_STATUSES;
        $ticketPriorities = self::TICKET_PRIORITIES;
        $categories = self::TICKET_CATEGORIES;
        $updates = $ticket->it_ticket_updates ?? [];
        usort($updates, fn($a, $b) => (int)$b->id <=> (int)$a->id);
        $this->set(compact('ticket', 'itUsers', 'ticketStatuses', 'ticketPriorities', 'categories', 'updates'));
    }

    private function setLookups($entity): void
    {
        $employees = $this->employeeOptions();
        $assets = $this->assetOptions();
        $itUsers = $this->itUserOptions();
        $categories = self::TICKET_CATEGORIES;
        $ticketStatuses = self::TICKET_STATUSES;
        $ticketPriorities = self::TICKET_PRIORITIES;
        $this->set(compact('entity', 'employees', 'assets', 'itUsers', 'categories', 'ticketStatuses', 'ticketPriorities'));
    }

    private function addUpdate(int $ticketId, string $status, string $note): void
    {
        $this->fetchTable('ItTicketUpdates')->save(
            $this->fetchTable('ItTicketUpdates')->newEntity([
                'ticket_id' => $ticketId,
                'status' => $status,
                'note' => $note,
                'actor_user_id' => (int)($this->Session->read('HrUser.id') ?: 0) ?: null,
                'created' => $this->now(),
            ])
        );
    }
}
