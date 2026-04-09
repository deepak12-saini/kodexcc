<?php
declare(strict_types=1);

namespace App\View\Helper;

use ArrayAccess;
use Cake\View\Helper;

/**
 * Legacy CakePHP 2: views use $this->params['controller'], ['action'], etc.
 * CakePHP 5 resolves $this->params to this helper (name "params" → class paramsHelper).
 */
class paramsHelper extends Helper implements ArrayAccess
{
    /**
     * @return array<string, mixed>
     */
    protected function routerParams(): array
    {
        $request = $this->getView()->getRequest();
        $query = $request->getQueryParams();

        return [
            'plugin' => $request->getParam('plugin'),
            'controller' => $request->getParam('controller'),
            'action' => $request->getParam('action'),
            'prefix' => $request->getParam('prefix'),
            'pass' => (array) $request->getParam('pass', []),
            'named' => $query,
            '?' => $query,
            '_ext' => $request->getParam('_ext'),
            'isAjax' => $request->is('ajax'),
        ];
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->routerParams());
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->routerParams()[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }
}
