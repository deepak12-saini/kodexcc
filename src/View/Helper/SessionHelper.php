<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Legacy CakePHP 2 compatibility: templates use $this->Session->read() and $this->Session->flash().
 */
class SessionHelper extends Helper
{
    /**
     * @var array<string>
     */
    protected array $helpers = ['Flash'];

    /**
     * Read a session value (dot paths supported).
     */
    public function read(?string $key = null): mixed
    {
        $session = $this->getView()->getRequest()->getSession();
        if ($key === null) {
            return null;
        }

        return $session->read($key);
    }

    /**
     * Render flash messages (replaces CakePHP 2 SessionHelper::flash()).
     */
    public function flash(): string
    {
        return $this->Flash->render() ?? '';
    }
}
