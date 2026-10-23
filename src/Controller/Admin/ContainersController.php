<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Utility\ContainerTransit;
use Cake\Event\EventInterface;

class ContainersController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
        $this->viewBuilder()->setLayout('admin_layout');
        $this->viewBuilder()->addHelper('Form');
        $this->checkAdminSession();
        $this->set('title_for_layout', 'Containers');
    }

    public function index(): void
    {
        $items = $this->fetchTable('IsoContainers')->find()
            ->orderBy(['IsoContainers.dispatched_on' => 'DESC', 'IsoContainers.id' => 'DESC'])
            ->all();
        $this->set('items', $items);
        $this->set('pageTitle', 'Containers');
    }

    public function form($id = null): ?\Cake\Http\Response
    {
        $table = $this->fetchTable('IsoContainers');
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            $containerNo = trim((string)($data['container_no'] ?? ''));
            $destination = trim((string)($data['destination'] ?? ''));
            $dispatched = trim((string)($data['dispatched_on'] ?? ''));
            if ($containerNo === '' || $destination === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dispatched)) {
                $this->Flash->error('Container number, destination, and the factory leave date are required.');
            } else {
                $patch = [
                    'container_no' => $containerNo,
                    'customer' => $this->blank($data['customer'] ?? ''),
                    'destination' => $destination,
                    'dispatched_on' => $dispatched,
                    'shipping_line' => $this->blank($data['shipping_line'] ?? ''),
                    'reference_no' => $this->blank($data['reference_no'] ?? ''),
                    'notes' => $this->blank($data['notes'] ?? ''),
                    'modified' => date('Y-m-d H:i:s'),
                ];
                if ($entity->isNew()) {
                    $patch['status'] = 'in_transit';
                    $patch['created'] = date('Y-m-d H:i:s');
                } elseif ((string)$entity->status === 'delivered' && $entity->delivered_on) {
                    $patch['days_taken'] = ContainerTransit::days($dispatched, $entity->delivered_on);
                }
                $entity = $table->patchEntity($entity, $patch);
                if ($table->save($entity)) {
                    $this->Flash->success('Container saved.');

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error('Could not save this container.');
            }
        }
        $this->set(compact('entity'));
        $this->set('pageTitle', $id ? 'Edit container' : 'Add container');

        return null;
    }

    public function deliver($id = null): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post']);
        $table = $this->fetchTable('IsoContainers');
        $entity = $table->get((int)$id);
        $delivered = trim((string)$this->request->getData('delivered_on'));
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $delivered)) {
            $delivered = date('Y-m-d');
        }
        $left = ContainerTransit::date($entity->dispatched_on);
        if ($delivered < $left) {
            $this->Flash->error('The delivered date cannot be before the factory leave date.');

            return $this->redirect(['action' => 'index']);
        }
        $entity = $table->patchEntity($entity, [
            'status' => 'delivered',
            'delivered_on' => $delivered,
            'days_taken' => ContainerTransit::days($left, $delivered),
            'modified' => date('Y-m-d H:i:s'),
        ]);
        if ($table->save($entity)) {
            $this->Flash->success('Container marked delivered. The timer is stopped.');
        } else {
            $this->Flash->error('Could not mark this container delivered.');
        }

        return $this->redirect(['action' => 'index']);
    }

    private function blank(mixed $value): ?string
    {
        $text = trim((string)$value);

        return $text === '' ? null : $text;
    }
}
