<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Staff DuroEzy spec mailers + EOI mailer logs.
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
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' DuroEzy Specification Mailer');
        $this->checkSatffSession();
        $customerId = (int)$this->Session->read('Customer.id');

        $this->paginate = [
            'limit' => 25,
            'order' => ['Mailer.id' => 'DESC'],
            'sortableFields' => [
                'Mailer.id',
                'Mailer.name',
                'Mailer.email',
                'Mailer.phone',
                'Mailer.company',
                'Mailer.project',
                'Mailer.type',
                'Mailer.created',
            ],
        ];

        $query = $this->fetchTable('Mailer')->find()
            ->where(['Mailer.user_id' => $customerId]);

        $page = $this->paginate($query);
        $this->set('mailersPaginated', $page);

        $mailers = [];
        foreach ($page as $entity) {
            if (!$entity instanceof EntityInterface) {
                continue;
            }
            $mailers[] = ['Mailer' => $entity->toArray()];
        }
        $this->set('mailers', $mailers);
    }

    public function send(): ?Response
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' DuroEzy Specification Mailer');
        $this->checkSatffSession();
        $userId = (int)$this->Session->read('Customer.id');

        if ($this->getRequest()->is('post')) {
            $d = $this->requestData();
            $name = (string)($d['name'] ?? '');
            $email = (string)($d['email'] ?? '');
            $phone = (string)($d['phone'] ?? '');
            $company = (string)($d['company'] ?? '');
            $project = (string)($d['project'] ?? '');
            $type = (string)($d['type'] ?? '');
            $subject = (string)($d['subject'] ?? '');

            $inserty = [
                'Mailer' => [
                    'user_id' => $userId,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'company' => $company,
                    'project' => $project,
                    'type' => $type,
                    'subject' => $subject,
                    'created' => date('Y-m-d H:i:s'),
                ],
            ];
            $this->Mailer->create();
            if ($this->Mailer->save($inserty)) {
                $url = SITEURL . 'DuroEzy/sendemail.php';
                $fields = [
                    'name' => urlencode($name),
                    'email' => urlencode($email),
                    'phone' => urlencode($phone),
                    'company' => urlencode($company),
                    'project' => urlencode($project),
                    'subject' => urlencode($subject),
                    'type' => urlencode($type),
                ];
                $fieldsString = '';
                foreach ($fields as $key => $value) {
                    $fieldsString .= $key . '=' . $value . '&';
                }
                $fieldsString = rtrim($fieldsString, '&');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                curl_close($ch);

                $this->Session->setFlash('Emailer sent successfully', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
        }

        return null;
    }

    public function eoi(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Staff Eoi Mailer');
        $this->checkSatffSession();
        $customerId = (int)$this->Session->read('Customer.id');

        $this->paginate = [
            'limit' => 25,
            'order' => ['EoiMailer.id' => 'DESC'],
            'sortableFields' => [
                'EoiMailer.id',
                'EoiMailer.inserindivdualname',
                'EoiMailer.insertcompanyname',
                'EoiMailer.projectname',
                'EoiMailer.date',
                'EoiMailer.insertname',
                'EoiMailer.mobile',
                'EoiMailer.landlineno',
                'EoiMailer.sender_email',
                'EoiMailer.subject',
                'EoiMailer.client_requested',
                'EoiMailer.created',
            ],
        ];

        $query = $this->fetchTable('EoiMailer')->find()
            ->where(['EoiMailer.user_id' => $customerId]);

        $page = $this->paginate($query);
        $this->set('eoiMailersPaginated', $page);

        $mailers = [];
        foreach ($page as $entity) {
            if (!$entity instanceof EntityInterface) {
                continue;
            }
            $mailers[] = ['EoiMailer' => $entity->toArray()];
        }
        $this->set('mailers', $mailers);
    }

    public function eoisend(): ?Response
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Send Eoi Mailer');
        $this->checkSatffSession();
        $userId = (int)$this->Session->read('Customer.id');

        if ($this->getRequest()->is('post')) {
            $d = $this->requestData();
            $names = $d['inserIndivdualtname'] ?? null;
            if (!empty($names) && is_array($names)) {
                $clientRequested = (string)($d['client_requested'] ?? '');
                for ($i = 0, $n = count($names); $i < $n; $i++) {
                    $inserIndivdualtname = (string)($names[$i] ?? '');
                    $insertcompanyname = (string)($this->arrayIndex($d, 'insertcompanyname', $i));
                    $insertbuildersname = (string)($this->arrayIndex($d, 'insertbuildersname', $i));
                    $projectname = (string)($this->arrayIndex($d, 'projectname', $i));
                    $dateRaw = $this->arrayIndex($d, 'date', $i);
                    $datenew = $this->parseDateToYmdFromArray($dateRaw);
                    $dateDisplay = $this->formatDateDmY($dateRaw);
                    $insertname = (string)($this->arrayIndex($d, 'insertname', $i));
                    $mobile = (string)($this->arrayIndex($d, 'mobile', $i));
                    $landline = (string)($this->arrayIndex($d, 'landline', $i));

                    $senderemail = (string)($this->arrayIndex($d, 'senderemail', $i));
                    if ($senderemail === '') {
                        $senderemail = 'sales@durotechindustries.com.au';
                    }
                    $subject = (string)($this->arrayIndex($d, 'subject', $i));
                    if ($subject === '') {
                        $subject = 'Durotech Industries :- Waterproofing and Epoxy Flooring';
                    }

                    $url = 'https://www.durotechindustries.com.au/cws/sendemail.php';
                    $fields = [
                        'inserIndivdualtname' => urlencode($inserIndivdualtname),
                        'insertcompanyname' => urlencode($insertcompanyname),
                        'insertbuildersname' => urlencode($insertbuildersname),
                        'projectname' => urlencode($projectname),
                        'date' => urlencode($dateDisplay),
                        'insertname' => urlencode($insertname),
                        'subject' => urlencode($subject),
                        'senderemail' => urlencode($senderemail),
                    ];
                    $fieldsString = '';
                    foreach ($fields as $key => $value) {
                        $fieldsString .= $key . '=' . $value . '&';
                    }
                    $fieldsString = rtrim($fieldsString, '&');
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, count($fields));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($ch);
                    curl_close($ch);

                    $mailerInsert = [
                        'EoiMailer' => [
                            'user_id' => $userId,
                            'inserindivdualname' => $inserIndivdualtname,
                            'insertcompanyname' => $insertcompanyname,
                            'insertbuilderemal' => $insertbuildersname,
                            'date' => $datenew,
                            'projectname' => $projectname,
                            'insertname' => $insertname,
                            'subject' => $subject,
                            'sender_email' => $senderemail,
                            'mobile' => $mobile,
                            'client_requested' => $clientRequested,
                            'landlineno' => $landline,
                            'created' => date('Y-m-d H:i:s'),
                            'status' => 1,
                        ],
                    ];
                    $this->EoiMailer->create();
                    $this->EoiMailer->save($mailerInsert);
                }
            }
            $this->Session->setFlash('Emailer sent successfully', 'default', ['class' => 'alert alert-success']);

            return $this->redirect(['action' => 'eoi']);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $d
     */
    private function arrayIndex(array $d, string $key, int $i): mixed
    {
        if (!isset($d[$key]) || !is_array($d[$key])) {
            return null;
        }

        return $d[$key][$i] ?? null;
    }

    private function parseDateToYmdFromArray(mixed $dateRaw): string
    {
        if ($dateRaw === null || $dateRaw === '') {
            return date('Y-m-d');
        }
        $s = (string)$dateRaw;
        $ts = strtotime($s);

        return $ts !== false ? date('Y-m-d', $ts) : date('Y-m-d');
    }

    private function formatDateDmY(mixed $dateRaw): string
    {
        if ($dateRaw === null || $dateRaw === '') {
            return date('d/m/Y');
        }
        $s = (string)$dateRaw;
        $ts = strtotime($s);

        return $ts !== false ? date('d/m/Y', $ts) : date('d/m/Y');
    }
}
