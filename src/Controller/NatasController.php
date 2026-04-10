<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Staff NATA calibration report (non-admin): /natas/...
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
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' NATA LIST');
        $this->checkSatffSession();

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

    public function addevent(): void
    {
        $this->checkSatffSession();
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $userId = $this->Session->read('Customer.id');

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
            $data['NataEvent']['user_id'] = $userId;
            $this->setRequestData($data);

            if ($this->NataEvent->save($data)) {
                $this->redirect(['action' => 'index']);
                return;
            }
        }

        $natacateArr = $this->NataCategory->find('all');
        $this->set('natacateArr', is_array($natacateArr) ? $natacateArr : []);
    }

    public function update($id = null, $status = null): void
    {
        $this->checkSatffSession();
        $this->viewBuilder()->disableAutoLayout();

        $userId = $this->Session->read('Customer.id');
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
                    'user_id' => $userId,
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
