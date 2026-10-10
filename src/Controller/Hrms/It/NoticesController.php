<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

use Cake\Event\EventInterface;
use Cake\Mailer\Mailer;

class NoticesController extends ItController
{
    private const PORTAL_URL = 'https://kodexcc.com/hrms';

    private const LOGO_URL = 'https://kodexcc.com/img/kodex-logo.png';

    private const DEFAULT_SUBJECT = 'Use the KodexCC portal for computer and hardware requests';

    private const DEFAULT_MESSAGE = <<<'TXT'
Please use the KodexCC portal for computer and hardware requests.

Sign in at https://kodexcc.com/hrms

My Assets shows the computer or other hardware assigned to you.

Submit Request is how you report a problem (for example a slow CPU or a repair). Choose Asset Repair / Replacement, write the problem, and submit. The request stays pending until admin acts.

Please record the request in the portal so it is not only sent on WhatsApp.
TXT;

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->requireHrRole(['admin']);
    }

    public function index()
    {
        $this->set('pageTitle', 'Notify employees');
        [$recipients, $skipped] = $this->audience();
        $subject = self::DEFAULT_SUBJECT;
        $message = self::DEFAULT_MESSAGE;
        $logoUrl = self::LOGO_URL;
        $portalUrl = self::PORTAL_URL;
        $this->set(compact('recipients', 'skipped', 'subject', 'message', 'logoUrl', 'portalUrl'));
    }

    public function send()
    {
        $this->request->allowMethod(['post']);
        $subject = trim((string)$this->request->getData('subject'));
        $message = trim((string)$this->request->getData('message'));
        if ($subject === '' || $message === '') {
            $this->Flash->error('Subject and message are required.');

            return $this->redirect(['action' => 'index']);
        }
        [$recipients, $skipped] = $this->audience();
        $selectedOnly = (string)$this->request->getData('audience') === 'selected';
        if ($selectedOnly) {
            $ids = array_map('intval', (array)$this->request->getData('employee_ids'));
            $recipients = array_values(array_filter(
                $recipients,
                static fn (array $person): bool => in_array($person['id'], $ids, true)
            ));
            if (!$recipients) {
                $this->Flash->error('Select at least one employee.');

                return $this->redirect(['action' => 'index']);
            }
        }
        $sent = 0;
        $failed = [];
        $actorId = (int)($this->Session->read('HrUser.id') ?: 0);
        foreach ($recipients as $person) {
            $plain = (int)$person['user_id'] === $actorId ? '' : $this->makePassword();
            try {
                $mailer = new Mailer();
                $mailer
                    ->setTransport('default')
                    ->setFrom(['info@kodexcc.com' => 'KodexCC'])
                    ->setTo($person['email'])
                    ->setSubject($subject)
                    ->setEmailFormat('html')
                    ->setViewVars([
                        'employeeName' => $person['name'],
                        'message' => $message,
                        'portalUrl' => self::PORTAL_URL,
                        'logoUrl' => self::LOGO_URL,
                        'username' => $person['username'],
                        'password' => $plain,
                    ]);
                $mailer->viewBuilder()
                    ->setTemplate('hardware_portal')
                    ->setLayout('hardware_portal');
                $mailer->deliver();
                if ($plain !== '') {
                    $this->storePassword((int)$person['user_id'], $plain);
                }
                $sent++;
            } catch (\Throwable $e) {
                $failed[] = $person['name'] . ' (' . $person['email'] . ')';
            }
        }
        $this->auditLog(
            'hardware_notice_send',
            'notice',
            'Hardware portal notice sent to ' . $sent . ' employee' . ($sent === 1 ? '' : 's') . ($selectedOnly ? ' (selected)' : ' (all)'),
            null,
            null,
            null,
            [
                'sent' => $sent,
                'failed' => $failed,
                'skipped' => count($skipped),
            ]
        );
        if ($sent > 0) {
            $this->Flash->success('Sent to ' . $sent . ' employee' . ($sent === 1 ? '' : 's') . '.');
        }
        if ($failed) {
            $this->Flash->error('Could not send to: ' . implode(', ', $failed));
        }
        if ($sent === 0 && !$failed) {
            $this->Flash->error('No employees with an email address to notify.');
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * @return array{0: list<array{id: int, user_id: int, name: string, email: string, username: string}>, 1: list<string>}
     */
    private function audience(): array
    {
        $rows = $this->fetchTable('HrEmployees')->find()
            ->contain(['HrUsers'])
            ->where(['HrEmployees.status' => 'active'])
            ->orderBy(['HrEmployees.full_name' => 'ASC'])
            ->all();
        $recipients = [];
        $skipped = [];
        foreach ($rows as $row) {
            $name = trim((string)$row->full_name) ?: 'Employee';
            $email = trim((string)$row->email);
            $user = $row->hr_user ?? null;
            $username = trim((string)($user->username ?? ''));
            if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $skipped[] = $name . ' (no email)';
                continue;
            }
            if ($user === null || $username === '' || !(int)$user->is_active) {
                $skipped[] = $name . ' (no portal login)';
                continue;
            }
            $recipients[] = [
                'id' => (int)$row->id,
                'user_id' => (int)$user->id,
                'name' => $name,
                'email' => $email,
                'username' => $username,
            ];
        }

        return [$recipients, $skipped];
    }

    private function makePassword(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $plain = '';
        for ($i = 0; $i < 10; $i++) {
            $plain .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $plain;
    }

    private function storePassword(int $userId, string $plain): void
    {
        $user = $this->fetchTable('HrUsers')->get($userId);
        $user->password = $this->hashPassword($plain);
        $user->modified = $this->now();
        $this->fetchTable('HrUsers')->save($user);
    }
}
