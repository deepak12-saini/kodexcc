<?php
declare(strict_types=1);

namespace App\Model;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Bridges legacy CakePHP 2-style model calls ($this->Model->find('first', ...), save, etc.)
 * used in migrated controllers with CakePHP 5 ORM tables.
 */
final class LegacyModelAdapter
{
    public int $recursive = 0;

    public mixed $id = null;

    public function __construct(private Table $table)
    {
    }

    public function __set(string $name, mixed $value): void
    {
        if ($name === 'id') {
            $this->id = $value;
        }
    }

    public function __get(string $name): mixed
    {
        if ($name === 'id') {
            return $this->id;
        }
        if ($name === 'primaryKey') {
            return $this->table->getPrimaryKey();
        }

        trigger_error(sprintf('Undefined property `%s::$%s`', self::class, $name), E_USER_NOTICE);

        return null;
    }

    /** @deprecated Legacy CakePHP 2 — clear id before insert */
    public function create(): void
    {
        $this->id = null;
    }

    /**
     * @param string|int|null $id When null, uses $this->id
     */
    public function exists(string|int|null $id = null): bool
    {
        $pk = $this->table->getPrimaryKey();
        if (!is_string($pk)) {
            return false;
        }
        $checkId = $id ?? $this->id;
        if ($checkId === null || $checkId === '') {
            return false;
        }

        return $this->table->exists([$pk => $checkId]);
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }
        try {
            $entity = $this->table->get($this->id);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException) {
            $this->id = null;

            return false;
        }
        $ok = $this->table->delete($entity);
        $this->id = null;

        return $ok;
    }

    /**
     * CakePHP 2 bindModel — no-op; use Table associations instead.
     *
     * @param mixed $config
     */
    public function bindModel($config): bool
    {
        return true;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function find(string $type = 'all', array $options = []): mixed
    {
        if ($type === 'count') {
            $query = $this->table->find();
            $query->applyOptions($options);

            return $query->count();
        }

        if ($type === 'first') {
            $query = $this->table->find();
            $query->applyOptions($options);
            $row = $query->first();

            return $this->wrapRow($row);
        }

        if ($type === 'all') {
            $query = $this->table->find();
            $query->applyOptions($options);
            $out = [];
            foreach ($query->all() as $row) {
                $out[] = $this->wrapRow($row);
            }

            return $out;
        }

        if ($type === 'list') {
            $opts = $options;
            $fields = $opts['fields'] ?? null;
            unset($opts['fields']);

            $alias = $this->table->getAlias();
            $pk = $this->table->getPrimaryKey();
            $pkName = is_string($pk) ? $pk : 'id';

            if (is_array($fields) && $fields !== []) {
                $fv = array_values($fields);
                if (count($fv) >= 2) {
                    $keyField = $fv[0];
                    $valueField = $fv[1];
                } else {
                    $keyField = $alias . '.' . $pkName;
                    $valueField = $fv[0];
                }
            } else {
                $keyField = $alias . '.' . $pkName;
                $valueField = $keyField;
            }

            $listOpts = $opts + [
                'keyField' => $keyField,
                'valueField' => $valueField,
            ];

            return $this->table->find('list', $listOpts)->toArray();
        }

        throw new \BadMethodCallException(sprintf('Unsupported legacy find type `%s` on table `%s`.', $type, $this->table->getAlias()));
    }

    /**
     * @param array<string, mixed> $data
     */
    public function save(array $data): bool
    {
        $alias = $this->table->getAlias();
        $row = $data[$alias] ?? $data;
        if (!is_array($row)) {
            return false;
        }
        if ($this->id !== null && !isset($row['id'])) {
            $row['id'] = $this->id;
        }
        $row = $this->stripUploadedFilesFromRow($row);
        $entity = $this->table->newEntity($row);
        $saved = $this->table->save($entity);
        if ($saved !== false) {
            $pk = $this->table->getPrimaryKey();
            if (is_string($pk) && $saved->has($pk)) {
                $this->id = $saved->get($pk);
            } else {
                $this->id = null;
            }
        } else {
            $this->id = null;
        }

        return $saved !== false;
    }

    public function saveField(string $field, mixed $value): bool
    {
        if ($this->id === null) {
            return false;
        }
        try {
            $entity = $this->table->get($this->id);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException) {
            return false;
        }
        $entity->set($field, $value);
        $saved = $this->table->save($entity);
        $this->id = null;

        return $saved !== false;
    }

    /**
     * Raw SQL (legacy). Prefer query builder for new code.
     *
     * @return list<array<string, mixed>>
     */
    public function query(string $sql): array
    {
        $conn = $this->table->getConnection();
        $stmt = $conn->execute($sql);

        return $stmt->fetchAll('assoc');
    }

    /**
     * CakePHP 5 merges PSR-7 UploadedFile instances into parsed POST; ORM cannot marshal them to strings.
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function stripUploadedFilesFromRow(array $row): array
    {
        foreach ($row as $key => $value) {
            if ($value instanceof UploadedFileInterface) {
                unset($row[$key]);
            } elseif (is_array($value)) {
                $row[$key] = $this->stripUploadedFilesFromRow($value);
            }
        }

        return $row;
    }

    private function wrapRow(mixed $row): array
    {
        if ($row === null) {
            return [];
        }

        $alias = $this->table->getAlias();
        if (is_array($row)) {
            return [$alias => $row];
        }
        if ($row instanceof EntityInterface) {
            return [$alias => $row->toArray()];
        }

        return [];
    }
}
