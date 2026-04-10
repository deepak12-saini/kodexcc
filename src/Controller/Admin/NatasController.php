<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Admin NATA calibration: /admin/natas/...
 */
class NatasController extends AppController
{
    public function __get(string $name): mixed
    {
        if (preg_match('/^[A-Z][A-Za-z0-9_]*$/', $name) === 1) {
            $adapter = $this->legacyModel($name);
            if ($adapter !== null) {
                return $adapter;
            }
        }

        return parent::__get($name);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
    }

    public function index($nextdate = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' NATA LIST');
        $this->checkAdminSession();

        if ($nextdate === null || $nextdate === '') {
            $nextdate = date('Y');
        }
        $nextdate = (string)$nextdate;

        $rows = $this->NataCategory->find('all', [
            'contain' => [
                'NataEvents' => function ($q) use ($nextdate) {
                    return $q->where(['NataEvents.year' => $nextdate]);
                },
            ],
        ]);
        $this->set('natacategory', $this->normalizeNataIndexRows(is_array($rows) ? $rows : []));
        $this->set('nextdate', $nextdate);
    }

    public function update($id = null, $status = null): void
    {
        $this->checkAdminSession();
        $this->viewBuilder()->disableAutoLayout();

        $userId = $this->Session->read('User.id');
        $wrapped = $this->NataEvent->find('first', [
            'conditions' => ['NataEvent.id' => $id],
            'contain' => ['NataCategory'],
        ]);
        $natacateArr = $this->normalizeNataEventDetail(is_array($wrapped) ? $wrapped : []);
        $this->set('natacateArr', $natacateArr);

        $post = $this->requestData();
        if (!empty($post['NataEvent'])) {
            $update = [
                'NataEvent' => [
                    'id' => $id,
                    'admin_id' => $userId,
                    'description' => $post['NataEvent']['description'] ?? '',
                    'status' => $post['NataEvent']['status'] ?? 0,
                ],
            ];
            $this->NataEvent->save($update);
            $this->Session->setFlash('Status updated successfully.', 'default', ['class' => 'alert alert-success']);
            $this->redirect(['action' => 'update', $id, 1]);
            return;
        }
        $this->set('status', $status);
    }

    public function addevent(): void
    {
        $this->checkAdminSession();
        $this->viewBuilder()->setLayout('admin_layout');
        $userId = $this->Session->read('User.id');

        $data = $this->requestData();
        if (!empty($data['NataEvent'])) {
            $ne = $data['NataEvent'];
            $monthInput = $ne['month'] ?? '';
            $monthyear = date('m-Y', strtotime((string)$monthInput));
            $year = date('Y', strtotime((string)$monthInput));

            $existing = $this->NataEvent->find('first', ['conditions' => ['NataEvent.month' => $monthyear]]);
            if (!empty($existing['NataEvent']['id'])) {
                $data['NataEvent']['id'] = $existing['NataEvent']['id'];
            }

            $data['NataEvent']['date'] = date('Y-m-d', strtotime((string)$monthInput));
            $data['NataEvent']['month'] = $monthyear;
            $data['NataEvent']['year'] = $year;
            $data['NataEvent']['admin_id'] = $userId;
            $this->setRequestData($data);

            if ($this->NataEvent->save($data)) {
                $this->redirect(['action' => 'index']);
                return;
            }
        }

        $natacateArr = $this->NataCategory->find('all');
        $this->set('natacateArr', is_array($natacateArr) ? $natacateArr : []);
    }

    public function clienttypeAdd(): void
    {
        $this->checkAdminSession();
        $this->viewBuilder()->setLayout('admin_layout');

        $data = $this->requestData();
        if (!empty($data['NataCategory'])) {
            $last = $this->NataCategory->find('first', ['order' => ['NataCategory.id' => 'DESC']]);
            if (!empty($last['NataCategory']['id'])) {
                $data['NataCategory']['unique_id'] = 1000 + (int)$last['NataCategory']['id'];
            } else {
                $data['NataCategory']['unique_id'] = 1000;
            }
            $this->setRequestData($data);

            if ($this->NataCategory->save($data)) {
                $this->redirect(['action' => 'clienttype']);
                return;
            }
        }

        $natacateArr = $this->NataCategory->find('all', ['conditions' => ['NataCategory.parent_id' => 0]]);
        $this->set('natacate', is_array($natacateArr) ? $natacateArr : []);
    }

    public function clienttypeEdit($id = null): void
    {
        $this->checkAdminSession();
        $this->viewBuilder()->setLayout('admin_layout');

        $data = $this->requestData();
        if (!empty($data['NataCategory'])) {
            $data['NataCategory']['id'] = $id;
            $this->setRequestData($data);
            if ($this->NataCategory->save($data)) {
                $this->redirect(['action' => 'clienttype']);
                return;
            }
        }

        $clientTypeArr = $this->NataCategory->find('first', ['conditions' => ['NataCategory.id' => $id]]);
        $this->set('ClientTypeArr', $clientTypeArr);
        if (!empty($clientTypeArr)) {
            $this->setRequestData($clientTypeArr);
        }

        $natacateArr = $this->NataCategory->find('all', ['conditions' => ['NataCategory.parent_id' => 0]]);
        $this->set('natacate', is_array($natacateArr) ? $natacateArr : []);
    }

    public function clienttype(): void
    {
        $this->checkAdminSession();
        $this->viewBuilder()->setLayout('admin_layout');

        $clientTypeArr = $this->NataCategory->find('all');
        $this->set('ClientTypeArr', is_array($clientTypeArr) ? $clientTypeArr : []);

        $natacate = [];
        foreach ((array)$clientTypeArr as $row) {
            $nc = $row['NataCategory'] ?? [];
            if (isset($nc['id'])) {
                $natacate[$nc['id']] = $nc['name'] ?? '';
            }
        }
        $this->set('natacate', $natacate);
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @return list<array{NataCategory: array<string, mixed>, NataEvent: list<array<string, mixed>>}>
     */
    private function normalizeNataIndexRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            $block = $row['NataCategory'] ?? [];
            $events = $block['nata_events'] ?? [];
            if (isset($block['nata_events'])) {
                unset($block['nata_events']);
            }
            $flat = [];
            foreach ((array)$events as $ev) {
                $flat[] = is_array($ev) ? $ev : [];
            }
            $out[] = ['NataCategory' => $block, 'NataEvent' => $flat];
        }

        return $out;
    }

    /**
     * @param array<string, mixed> $wrapped
     * @return array{NataEvent: array<string, mixed>, NataCategory: array<string, mixed>}
     */
    private function normalizeNataEventDetail(array $wrapped): array
    {
        if ($wrapped === []) {
            return ['NataEvent' => [], 'NataCategory' => []];
        }
        $ev = $wrapped['NataEvent'] ?? [];
        $cat = $ev['nata_category'] ?? [];
        if (isset($ev['nata_category'])) {
            unset($ev['nata_category']);
        }

        return [
            'NataEvent' => is_array($ev) ? $ev : [],
            'NataCategory' => is_array($cat) ? $cat : [],
        ];
    }
}
