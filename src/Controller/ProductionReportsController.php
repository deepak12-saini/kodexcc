<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Staff production reports (URLs: /production-reports/...).
 */
class ProductionReportsController extends AppController
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

    public function index(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Production Report');
        $this->checkSatffSession();

        $rows = $this->ProductionReport->find('all', [
            'contain' => ['NappUser'],
            'order' => ['ProductionReport.id' => 'DESC'],
        ]);
        $this->normalizeNappUserRows($rows);
        $this->set('ProductionReport', $rows);
        $this->set('user_id', $this->Session->read('Customer.id'));
    }

    public function add(): ?Response
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Add Production Report');
        $this->checkSatffSession();
        $userId = $this->Session->read('Customer.id');

        $post = $this->legacyProductionReportPost();
        if ($post !== null) {
            $docArr = $this->ProductionReport->find('first', [
                'order' => ['ProductionReport.id' => 'DESC'],
                'fields' => ['ProductionReport.id'],
            ]);
            if (!empty($docArr['ProductionReport']['id'])) {
                $uniqueid = 'KD-PDR-' . (1000 + $docArr['ProductionReport']['id'] + 1);
            } else {
                $uniqueid = 'KD-PDR-1001';
            }
            $post['ProductionReport']['uniqueid'] = $uniqueid;
            $post['ProductionReport']['user_id'] = $userId;
            $post['ProductionReport']['date'] = date('Y-m-d H:i:s');
            $this->setRequestData($post);
            if ($this->ProductionReport->save($post)) {
                $dt = date('d M Y');
                $to = 'rsb@kodexglobalcc.com';
                $subject = SITENAME . ' :: Production Report (' . $dt . ')';
                $templateName = 'production_report';
                $name = $this->Session->read('Customer.name') . ' ' . $this->Session->read('Customer.lname');
                $url = SITEURL . 'production-reports/autologin-admin';
                $variables = ['name' => $name, 'uniqueid' => $uniqueid, 'url' => $url];
                try {
                    $this->sendemail($to, $subject, $templateName, $variables);
                } catch (\Exception) {
                }

                $this->Session->setFlash('Added successfully.', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
        }

        return null;
    }

    /**
     * Legacy autologin link for email (logs in first admin user).
     */
    public function autologinAdmin(): Response
    {
        $this->autoRender = false;

        $adminArr = $this->User->find('first');
        if (!empty($adminArr['User'])) {
            $insert = [
                'LoginHistory' => [
                    'user_id' => $adminArr['User']['id'],
                    'role' => 'Admin',
                    'logintime' => date('Y-m-d H:i:s'),
                ],
            ];
            $this->LoginHistory->save($insert);

            $this->Session->write('User', $adminArr['User']);
            $this->Session->write('is_admin', 1);

            return $this->redirect('/admin/production-reports');
        }

        $this->Session->setFlash('Wrong username/password', 'default', ['class' => 'alert alert-danger']);

        return $this->redirect('/admin');
    }

    public function edit(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Production Report');
        $this->checkSatffSession();
        $userId = $this->Session->read('Customer.id');

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid production report.');
        }

        $post = $this->legacyProductionReportPost();
        if ($post !== null) {
            $post['ProductionReport']['id'] = $id;
            $post['ProductionReport']['user_id'] = $userId;
            $this->setRequestData($post);
            if ($this->ProductionReport->save($post)) {
                $this->Session->setFlash('Updated successfully.', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
        }

        $productionReport = $this->ProductionReport->find('first', [
            'conditions' => ['ProductionReport.id' => $id],
            'contain' => ['NappUser'],
        ]);
        $this->normalizeNappUserRow($productionReport);
        $this->set('ProductionReport', $productionReport);

        return null;
    }

    /**
     * @return array<string, mixed>|null POST body with top-level ProductionReport key, or null if not POST.
     */
    private function legacyProductionReportPost(): ?array
    {
        if (!$this->getRequest()->is('post')) {
            return null;
        }
        $d = $this->requestData();
        if (isset($d['data']['ProductionReport'])) {
            return ['ProductionReport' => $d['data']['ProductionReport']];
        }
        if (isset($d['ProductionReport'])) {
            return ['ProductionReport' => $d['ProductionReport']];
        }

        return null;
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function normalizeNappUserRows(array &$rows): void
    {
        foreach ($rows as &$row) {
            $this->normalizeNappUserRow($row);
        }
        unset($row);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function normalizeNappUserRow(array &$row): void
    {
        if (!empty($row['ProductionReport']['napp_user'])) {
            $row['NappUser'] = $row['ProductionReport']['napp_user'];
            unset($row['ProductionReport']['napp_user']);
        }
    }
}
