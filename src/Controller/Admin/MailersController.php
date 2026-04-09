<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Admin DuroEzy spec mailers: /admin/mailers/...
 */
class MailersController extends AppController
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
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' DuroEzy Specification Mailer');
        $this->checkAdminSession();
        $userId = (int)$this->Session->read('User.id');

        $this->paginate = [
            'limit' => 25,
            'order' => ['Mailer.id' => 'DESC'],
            'sortableFields' => [
                'Mailer.tracknumber',
                'Mailer.name',
                'Mailer.email',
                'Mailer.company',
                'Mailer.address',
                'Mailer.specification',
                'Mailer.date',
                'Mailer.type',
                'Mailer.subject',
                'Mailer.created',
                'User.name',
                'User.username',
            ],
        ];

        $ownerColumn = $this->mailerOwnerColumn();
        $query = $this->fetchTable('Mailer')->find()
            ->where(['Mailer.' . $ownerColumn => $userId])
            ->contain(['User']);

        $page = $this->paginate($query);
        $this->set('mailersPaginated', $page);

        $mailers = [];
        foreach ($page as $entity) {
            if (!$entity instanceof EntityInterface) {
                continue;
            }
            $m = $entity->toArray();
            unset($m['user']);
            $userArr = [];
            if ($entity->has('user') && $entity->get('user') !== null) {
                $userArr = $entity->get('user')->toArray();
            }
            $mailers[] = [
                'Mailer' => $m,
                'User' => $userArr,
            ];
        }
        $this->set('mailers', $mailers);
    }

    public function send(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' DuroEzy Specification Mailer');
        $this->checkAdminSession();
        $userId = (int)$this->Session->read('User.id');

        if ($this->getRequest()->is('post')) {
            $d = $this->requestData();
            $tracknumber = (string)($d['tracknumber'] ?? '');
            $name = (string)($d['name'] ?? '');
            $email = (string)($d['email'] ?? '');
            $company = (string)($d['company'] ?? '');
            $address = (string)($d['address'] ?? '');
            $specification = (string)($d['specification'] ?? '');
            $dateRaw = $d['date'] ?? '';
            $type = (string)($d['type'] ?? '');
            $subject = (string)($d['subject'] ?? '');

            $dateSql = $this->parseDateToYmd($dateRaw);

            $ownerColumn = $this->mailerOwnerColumn();
            $inserty = [
                'Mailer' => [
                    $ownerColumn => $userId,
                    'name' => $name,
                    'tracknumber' => $tracknumber,
                    'address' => $address,
                    'specification' => $specification,
                    'date' => $dateSql,
                    'email' => $email,
                    'company' => $company,
                    'type' => $type,
                    'subject' => $subject,
                    'created' => date('Y-m-d H:i:s'),
                ],
            ];
            $this->Mailer->create();
            if ($this->Mailer->save($inserty)) {
                $lastinsertId = (int)$this->Mailer->id;
                $pdfname = $lastinsertId . '-' . $tracknumber . '.pdf';
                $fields = [
                    'name' => urlencode($name),
                    'email' => urlencode($email),
                    'address' => urlencode($address),
                    'company' => urlencode($company),
                    'specification' => urlencode($specification),
                    'date' => urlencode((string)$dateRaw),
                    'subject' => urlencode($subject),
                    'type' => urlencode($type),
                    'pdfname' => urlencode($pdfname),
                ];

                $fieldsString = '';
                foreach ($fields as $key => $value) {
                    $fieldsString .= $key . '=' . $value . '&';
                }
                $fieldsString = rtrim($fieldsString, '&');
                $url = SITEURL . 'dompdf/generatepdf.php';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                curl_close($ch);

                $this->Session->setFlash('Emailer sent successfully', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['prefix' => 'Admin', 'controller' => 'Mailers', 'action' => 'index']);
            }
        }

        return null;
    }

    /**
     * POST to legacy `DuroEzy/adminsendemail.php`. Routed as /admin/mailers/sendemail/{id}
     * (see config/routes.php) — name avoids clashing with AppController::sendemail().
     */
    public function sendSpecificationEmail(?string $id = null): ?Response
    {
        $this->checkAdminSession();
        $this->autoRender = false;

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid mailer.');
        }

        $mailerArr = $this->Mailer->find('first', [
            'conditions' => ['Mailer.id' => $id],
        ]);
        if (!empty($mailerArr['Mailer'])) {
            $row = $mailerArr['Mailer'];
            $pdfname = $row['id'] . '-' . ($row['tracknumber'] ?? '') . '.pdf';
            $email = (string)($row['email'] ?? '');
            $subject = (string)($row['subject'] ?? '');
            $fields = [
                'email' => urlencode($email),
                'pdfname' => urlencode($pdfname),
                'subject' => urlencode($subject),
            ];
            $fieldsString = '';
            foreach ($fields as $key => $value) {
                $fieldsString .= $key . '=' . $value . '&';
            }
            $fieldsString = rtrim($fieldsString, '&');
            $url = SITEURL . 'DuroEzy/adminsendemail.php';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);

            $this->Session->setFlash('Emailer sent successfully', 'default', ['class' => 'alert alert-success']);

            return $this->redirect(['prefix' => 'Admin', 'controller' => 'Mailers', 'action' => 'index']);
        }

        $this->Session->setFlash('record not found.', 'default', ['class' => 'alert alert-success']);

        return $this->redirect(['prefix' => 'Admin', 'controller' => 'Mailers', 'action' => 'index']);
    }

    /**
     * @param mixed $dateRaw
     */
    private function parseDateToYmd(mixed $dateRaw): string
    {
        if ($dateRaw === null || $dateRaw === '') {
            return date('Y-m-d');
        }
        $s = (string)$dateRaw;
        $ts = strtotime(str_replace('/', '-', $s));

        return $ts !== false ? date('Y-m-d', $ts) : date('Y-m-d');
    }

    /**
     * Prefer `admin_id`; fallback to `user_id` for legacy stub schema.
     */
    private function mailerOwnerColumn(): string
    {
        $schema = $this->fetchTable('Mailer')->getSchema();
        if ($schema->hasColumn('admin_id')) {
            return 'admin_id';
        }

        return 'user_id';
    }
}
