<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Utility\ErpAccess;
use App\Utility\IsoAccess;
use Cake\Event\EventInterface;
use Cake\Http\Response;

class ErpController extends AppController
{
    use ErpPurchaseTrait;
    use ErpInventoryTrait;
    use ErpProductionTrait;
    use ErpSalesTrait;
    use ErpBooksTrait;

    public const PARTNER_TYPES = [
        'customer' => 'Customer',
        'supplier' => 'Supplier',
    ];

    public const STATUSES = [
        'active' => 'Active',
        'blocked' => 'Blocked',
    ];

    public const SETTINGS = [
        'company_name' => ['label' => 'Company name', 'required' => true, 'placeholder' => 'Name printed on invoices and orders'],
        'gstin' => ['label' => 'Company GSTIN', 'placeholder' => '15-character GST number'],
        'state' => ['label' => 'State', 'placeholder' => 'Decides CGST + SGST or IGST on invoices'],
        'address' => ['label' => 'Registered address', 'type' => 'textarea', 'placeholder' => 'Address printed on documents'],
        'financial_year_start' => ['label' => 'Financial year starts (MM-DD)', 'placeholder' => '04-01 for 1 April'],
        'currency' => ['label' => 'Currency', 'placeholder' => 'INR'],
    ];

    private const GSTIN_PATTERN = '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/';

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
        $this->viewBuilder()->setLayout('admin_layout');
        $this->viewBuilder()->addHelper('Paginator');
        $this->viewBuilder()->addHelper('Form');
        $role = $this->erpRole();
        if ($role === null) {
            $this->Flash->error('You need to be logged in to access this area.');

            return $this->redirect('/admin');
        }
        $section = $this->sectionFor((string)$this->request->getParam('action'));
        $viewSection = match ($section) {
            'purchase_approve' => 'purchase',
            'sales_approve' => 'sales',
            default => $section,
        };
        if (!ErpAccess::canView($role, $viewSection)) {
            $this->Flash->error('Your role cannot open that ERP screen.');

            return $this->redirect(ErpAccess::canView($role, 'overview') ? ['action' => 'index'] : '/admin/iso');
        }
        if ($this->request->is(['post', 'put', 'delete']) && !ErpAccess::canWrite($role, $section)) {
            $this->Flash->error('Your role can view this screen but cannot change it.');

            return $this->redirect(['action' => $this->request->getParam('action')]);
        }
        $this->set('title_for_layout', 'ERP');
        $this->set('basePath', 'admin/erp/');
        $names = [];
        foreach ($this->fetchTable('HrEmployees')->find()->where(['status' => 'active'])->orderBy(['full_name' => 'ASC'])->limit(200) as $person) {
            $names[] = (string)$person->full_name;
        }
        $this->set('isoPeople', $names);
        $this->set('erpRoleLabel', IsoAccess::ROLES[$role] ?? $role);
        $this->set('canEdit', ErpAccess::canWrite($role, $viewSection));
        $this->set('canApprove', ErpAccess::canWrite($role, 'purchase_approve'));
        $this->set('canApproveSales', ErpAccess::canWrite($role, 'sales_approve'));
    }

    public function index(): void
    {
        $role = $this->erpRole() ?? '';
        $partners = $this->fetchTable('ErpPartners');
        $cards = [
            ['label' => 'Active customers', 'count' => $partners->find()->where(['partner_type' => 'customer', 'status' => 'active'])->count(), 'url' => 'partners?partner_type=customer&status=active', 'icon' => 'fa-handshake-o', 'tone' => 'blue'],
            ['label' => 'Active suppliers', 'count' => $partners->find()->where(['partner_type' => 'supplier', 'status' => 'active'])->count(), 'url' => 'partners?partner_type=supplier&status=active', 'icon' => 'fa-truck', 'tone' => 'green'],
            ['label' => 'ISO suppliers not in ERP', 'count' => count($this->unlinkedIsoSuppliers()), 'url' => 'partners?partner_type=supplier', 'icon' => 'fa-link', 'tone' => 'orange'],
            ['label' => 'Blocked partners', 'count' => $partners->find()->where(['status' => 'blocked'])->count(), 'url' => 'partners?status=blocked', 'icon' => 'fa-ban', 'tone' => 'red'],
            ['label' => 'Orders to receive', 'count' => $this->fetchTable('ErpPurchaseOrders')->find()->where(['status IN' => ['approved', 'partial']])->count(), 'url' => 'purchasing', 'icon' => 'fa-shopping-cart', 'tone' => 'blue'],
            ['label' => 'Stock below reorder', 'count' => $this->lowStockCount(), 'url' => 'inventory', 'icon' => 'fa-cubes', 'tone' => 'orange'],
            ['label' => 'Unpaid sales invoices', 'count' => $this->unpaidSalesCount(), 'url' => 'outstanding', 'icon' => 'fa-inr', 'tone' => 'red'],
        ];
        $modules = [
            ['section' => 'partners', 'label' => 'Business partners', 'hint' => 'Customers and suppliers with GST, credit terms and contacts', 'icon' => 'fa-address-book', 'url' => 'partners'],
            ['section' => 'purchase', 'label' => 'Purchasing', 'hint' => 'Request, quotation, order, goods receipt and supplier bill', 'icon' => 'fa-shopping-cart', 'url' => 'purchasing'],
            ['section' => 'inventory', 'label' => 'Inventory', 'hint' => 'Stock by godown and lot, after QC pass', 'icon' => 'fa-cubes', 'url' => 'inventory'],
            ['section' => 'production', 'label' => 'Production', 'hint' => 'Bill of materials, issue and finished output', 'icon' => 'fa-industry', 'url' => 'production'],
            ['section' => 'sales', 'label' => 'Sales', 'hint' => 'Quotation, order, delivery and GST invoice', 'icon' => 'fa-line-chart', 'url' => 'sales'],
            ['section' => 'banking', 'label' => 'Banking', 'hint' => 'Receipts, payments and reconciliation', 'icon' => 'fa-bank', 'url' => 'banking'],
            ['section' => 'financials', 'label' => 'Financials', 'hint' => 'Journals, trial balance, profit and loss, GST', 'icon' => 'fa-book', 'url' => 'financials'],
            ['section' => 'admin', 'label' => 'Units', 'hint' => 'KG, litre, numbers and other units of measure', 'icon' => 'fa-balance-scale', 'url' => 'units'],
            ['section' => 'admin', 'label' => 'Godowns', 'hint' => 'Stores where stock is kept', 'icon' => 'fa-building', 'url' => 'godowns'],
            ['section' => 'admin', 'label' => 'Company settings', 'hint' => 'Company GSTIN, state and financial year', 'icon' => 'fa-cog', 'url' => 'settings'],
            ['section' => 'admin', 'label' => 'Change log', 'hint' => 'Who created, changed or deleted ERP records', 'icon' => 'fa-history', 'url' => 'audit-log'],
        ];
        $modules = array_values(array_filter($modules, function (array $module) use ($role): bool {
            return ErpAccess::canView($role, $module['section']);
        }));
        $upcoming = [];
        $this->set('pageTitle', 'ERP');
        $this->set(compact('cards', 'modules', 'upcoming'));
    }

    public function partners(): ?Response
    {
        if ($this->request->is('post') && $this->request->getData('import_iso')) {
            return $this->importIsoSuppliers();
        }

        return $this->renderList('Business partners', 'erp-partners.csv', 'ErpPartners', [
            'Code', 'Type', 'Name', 'GSTIN', 'State', 'Contact', 'Phone', 'Credit days', 'Status',
        ], [
            'selects' => ['partner_type' => self::PARTNER_TYPES, 'status' => self::STATUSES],
            'search' => ['code', 'name', 'gstin', 'contact_person', 'phone', 'email', 'city'],
        ], function ($row) {
            return [
                $row->code,
                self::PARTNER_TYPES[$row->partner_type] ?? $row->partner_type,
                $row->name,
                $row->gstin,
                $row->state,
                $row->contact_person,
                $row->phone,
                $row->credit_days,
                self::STATUSES[$row->status] ?? $row->status,
            ];
        }, 'partnerForm', 'partners', function (): void {
            $this->set('importCount', count($this->unlinkedIsoSuppliers()));
        });
    }

    public function partnerForm($id = null): ?Response
    {
        return $this->renderForm('Business partner', 'ErpPartners', 'partners', $id, [
            'partner_type' => ['label' => 'Type', 'type' => 'select', 'required' => true, 'options' => self::PARTNER_TYPES, 'default' => (string)$this->request->getQuery('type') ?: null, 'placeholder' => 'Customer or supplier'],
            'code' => ['label' => 'Code', 'placeholder' => 'Leave empty to fill automatically (CUS-0001 or SUP-0001)'],
            'name' => ['label' => 'Name', 'required' => true, 'placeholder' => 'Company name as on GST certificate'],
            'gstin' => ['label' => 'GSTIN', 'placeholder' => '15 characters, e.g. 03ABCDE1234F1Z5'],
            'pan' => ['label' => 'PAN', 'placeholder' => '10 characters'],
            'state' => ['label' => 'State', 'placeholder' => 'State for GST, e.g. Punjab'],
            'city' => ['label' => 'City', 'placeholder' => 'City'],
            'contact_person' => ['label' => 'Contact person', 'placeholder' => 'Name of the person you deal with'],
            'phone' => ['label' => 'Phone', 'placeholder' => 'Mobile or landline'],
            'email' => ['label' => 'Email', 'placeholder' => 'Email for orders and invoices'],
            'credit_days' => ['label' => 'Credit days', 'placeholder' => 'Days allowed to pay, e.g. 30'],
            'credit_limit' => ['label' => 'Credit limit (INR)', 'placeholder' => 'Maximum outstanding allowed'],
            'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'options' => self::STATUSES, 'default' => 'active', 'placeholder' => 'Blocked partners cannot be used on new documents'],
            'address' => ['label' => 'Address', 'type' => 'textarea', 'placeholder' => 'Billing address'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Payment terms, bank details or other notes'],
        ], function ($table, array $data, $existing) {
            $data['gstin'] = strtoupper(str_replace(' ', '', (string)($data['gstin'] ?? '')));
            $data['pan'] = strtoupper(str_replace(' ', '', (string)($data['pan'] ?? '')));
            if ($data['gstin'] !== '' && !preg_match(self::GSTIN_PATTERN, $data['gstin'])) {
                return 'GSTIN should be 15 characters, like 03ABCDE1234F1Z5.';
            }
            if (($data['email'] ?? '') !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return 'Email address is not valid.';
            }
            foreach (['credit_days', 'credit_limit'] as $key) {
                $value = str_replace(',', '', (string)($data[$key] ?? ''));
                if ($value !== '' && !is_numeric($value)) {
                    return 'Credit days and credit limit must be numbers.';
                }
                $data[$key] = $value === '' ? null : $value;
            }
            if (($data['code'] ?? '') === '') {
                $data['code'] = $this->nextCode($data['partner_type'] === 'supplier' ? 'SUP' : 'CUS');
            }
            $clash = $table->find()->where(['code' => $data['code']]);
            if ($existing) {
                $clash->where(['id !=' => $existing->id]);
            }
            if ($clash->count() > 0) {
                return 'Code ' . $data['code'] . ' is already used by another partner.';
            }

            return $data;
        }, function ($partner): void {
            $this->syncIsoSupplier($partner);
        });
    }

    public function units(): ?Response
    {
        return $this->renderList('Units', 'erp-units.csv', 'ErpUnits', ['Code', 'Name', 'Status'], [
            'selects' => ['status' => ['active' => 'Active', 'inactive' => 'Inactive']],
            'search' => ['code', 'name'],
        ], function ($row) {
            return [$row->code, $row->name, $row->status];
        }, 'unitForm', 'units');
    }

    public function unitForm($id = null): ?Response
    {
        return $this->renderForm('Unit', 'ErpUnits', 'units', $id, [
            'code' => ['label' => 'Code', 'required' => true, 'placeholder' => 'Short code, e.g. KG'],
            'name' => ['label' => 'Name', 'required' => true, 'placeholder' => 'Full name, e.g. Kilogram'],
            'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['active' => 'Active', 'inactive' => 'Inactive'], 'default' => 'active'],
        ], function ($table, array $data, $existing) {
            $data['code'] = strtoupper((string)$data['code']);

            return $this->uniqueCode($table, $data, $existing, 'Unit');
        });
    }

    public function godowns(): ?Response
    {
        return $this->renderList('Godowns', 'erp-godowns.csv', 'ErpGodowns', ['Code', 'Name', 'Location', 'Status'], [
            'selects' => ['status' => ['active' => 'Active', 'inactive' => 'Inactive']],
            'search' => ['code', 'name', 'location'],
        ], function ($row) {
            return [$row->code, $row->name, $row->location, $row->status];
        }, 'godownForm', 'godowns');
    }

    public function godownForm($id = null): ?Response
    {
        return $this->renderForm('Godown', 'ErpGodowns', 'godowns', $id, [
            'code' => ['label' => 'Code', 'required' => true, 'placeholder' => 'Short code, e.g. RM'],
            'name' => ['label' => 'Name', 'required' => true, 'placeholder' => 'e.g. Raw material store'],
            'location' => ['label' => 'Location', 'placeholder' => 'Building or site'],
            'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['active' => 'Active', 'inactive' => 'Inactive'], 'default' => 'active'],
        ], function ($table, array $data, $existing) {
            $data['code'] = strtoupper((string)$data['code']);

            return $this->uniqueCode($table, $data, $existing, 'Godown');
        });
    }

    public function settings(): ?Response
    {
        $table = $this->fetchTable('ErpSettings');
        $values = [];
        foreach ($table->find() as $row) {
            $values[$row->setting_key] = (string)$row->setting_value;
        }
        if ($this->request->is(['post', 'put'])) {
            $posted = [];
            foreach (self::SETTINGS as $key => $field) {
                $posted[$key] = trim((string)$this->request->getData($key));
            }
            $posted['gstin'] = strtoupper(str_replace(' ', '', $posted['gstin']));
            $error = null;
            if ($posted['company_name'] === '') {
                $error = 'Company name is required.';
            } elseif ($posted['gstin'] !== '' && !preg_match(self::GSTIN_PATTERN, $posted['gstin'])) {
                $error = 'GSTIN should be 15 characters, like 03ABCDE1234F1Z5.';
            } elseif (!preg_match('/^(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $posted['financial_year_start'])) {
                $error = 'Financial year start should look like 04-01.';
            }
            if ($error !== null) {
                $this->Flash->error($error);
                $values = $posted + $values;
            } else {
                foreach ($posted as $key => $value) {
                    $row = $table->find()->where(['setting_key' => $key])->first() ?? $table->newEntity(['setting_key' => $key]);
                    $row->setting_value = $value;
                    $row->modified = date('Y-m-d H:i:s');
                    $table->save($row);
                }
                $this->audit('update', 'settings', null, 'Company settings updated');
                $this->Flash->success('Company settings saved.');

                return $this->redirect(['action' => 'settings']);
            }
        }
        $this->set('pageTitle', 'Company settings');
        $this->set('fields', self::SETTINGS);
        $this->set('settingValues', $values);

        return null;
    }

    public function auditLog(): ?Response
    {
        return $this->renderList('Change log', 'erp-change-log.csv', 'ErpAuditLogs', ['When', 'Who', 'Action', 'Record', 'Summary'], [
            'selects' => ['action' => ['create' => 'Create', 'update' => 'Update', 'delete' => 'Delete', 'import' => 'Import']],
            'search' => ['actor', 'entity', 'summary'],
            'dates' => 'created',
            'readonly' => true,
        ], function ($row) {
            return [(string)$row->created, $row->actor, $row->action, $row->entity . ($row->entity_id ? ' #' . $row->entity_id : ''), $row->summary];
        }, '', 'auditLog');
    }

    private function importIsoSuppliers(): Response
    {
        $added = 0;
        foreach ($this->unlinkedIsoSuppliers() as $iso) {
            $partner = $this->fetchTable('ErpPartners')->newEntity([
                'code' => $this->nextCode('SUP'),
                'partner_type' => 'supplier',
                'name' => $iso->name,
                'contact_person' => $iso->contact,
                'phone' => $iso->phone,
                'iso_supplier_id' => $iso->id,
                'status' => $iso->status === 'blocked' ? 'blocked' : 'active',
                'notes' => $iso->material ? 'Supplies: ' . $iso->material : null,
                'created_by' => $this->adminId(),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($this->fetchTable('ErpPartners')->save($partner)) {
                $added++;
            }
        }
        if ($added > 0) {
            $this->audit('import', 'ErpPartners', null, $added . ' supplier(s) imported from the ISO approved supplier list');
            $this->Flash->success($added . ' ISO supplier(s) added as business partners.');
        } else {
            $this->Flash->success('Every ISO supplier is already a business partner.');
        }

        return $this->redirect(['action' => 'partners', '?' => ['partner_type' => 'supplier']]);
    }

    /**
     * @return list<\Cake\Datasource\EntityInterface>
     */
    private function unlinkedIsoSuppliers(): array
    {
        $linked = $this->fetchTable('ErpPartners')->find()
            ->select(['iso_supplier_id'])
            ->where(['iso_supplier_id IS NOT' => null]);

        return $this->fetchTable('IsoSuppliers')->find()
            ->where(['id NOT IN' => $linked])
            ->orderBy(['name' => 'ASC'])
            ->all()
            ->toList();
    }

    private function syncIsoSupplier($partner): void
    {
        if ($partner->partner_type !== 'supplier') {
            return;
        }
        $iso = $this->fetchTable('IsoSuppliers');
        $row = $partner->iso_supplier_id ? $iso->find()->where(['id' => $partner->iso_supplier_id])->first() : null;
        if ($row === null) {
            $row = $iso->newEntity([
                'status' => $partner->status === 'blocked' ? 'blocked' : 'approved',
                'created_by' => $this->adminId(),
                'created' => date('Y-m-d H:i:s'),
            ]);
        }
        $row->name = $partner->name;
        $row->contact = $partner->contact_person;
        $row->phone = $partner->phone;
        $row->modified = date('Y-m-d H:i:s');
        if ($iso->save($row) && (int)$partner->iso_supplier_id !== (int)$row->id) {
            $partner->iso_supplier_id = $row->id;
            $this->fetchTable('ErpPartners')->save($partner);
        }
    }

    private function uniqueCode($table, array $data, $existing, string $label): array|string
    {
        $clash = $table->find()->where(['code' => $data['code']]);
        if ($existing) {
            $clash->where(['id !=' => $existing->id]);
        }

        return $clash->count() > 0 ? $label . ' code ' . $data['code'] . ' already exists.' : $data;
    }

    private function nextCode(string $prefix): string
    {
        $last = $this->fetchTable('ErpPartners')->find()
            ->select(['code'])
            ->where(['code LIKE' => $prefix . '-%'])
            ->orderBy(['id' => 'DESC'])
            ->first();
        $n = 1;
        if ($last && preg_match('/(\d+)$/', (string)$last->code, $m)) {
            $n = (int)$m[1] + 1;
        }

        return sprintf('%s-%04d', $prefix, $n);
    }

    private function renderList(string $title, string $file, string $alias, array $header, array $filter, callable $map, string $addAction, string $selfAction, ?callable $extra = null): ?Response
    {
        if ($this->request->is('post') && $this->request->getData('delete_id') && empty($filter['readonly'])) {
            $table = $this->fetchTable($alias);
            $row = $table->find()->where(['id' => (int)$this->request->getData('delete_id')])->first();
            if ($row && !empty($filter['deleteCheck'])) {
                $reason = ($filter['deleteCheck'])($row);
                if (is_string($reason)) {
                    $this->Flash->error($reason);

                    return $this->redirect(['action' => $selfAction]);
                }
            }
            if ($row && !empty($filter['lineTable'])) {
                $this->fetchTable($filter['lineTable'])->deleteAll([$filter['lineFk'] => $row->id]);
            }
            if ($row && $table->delete($row)) {
                $this->audit('delete', $alias, (int)$row->id, $title . ': ' . ($row->code ?? $row->name ?? '') . ' deleted');
                $this->Flash->success($title . ' record deleted.');
            } else {
                $this->Flash->error('Could not delete this record.');
            }

            return $this->redirect(['action' => $selfAction]);
        }
        $query = $this->fetchTable($alias)->find()->orderBy([$alias . '.id' => 'DESC']);
        $values = [];
        foreach ($filter['selects'] ?? [] as $key => $options) {
            $value = trim((string)$this->request->getQuery($key));
            $values[$key] = $value;
            if ($value !== '' && array_key_exists($value, $options)) {
                $query->where([$alias . '.' . $key => $value]);
            }
        }
        $q = trim((string)$this->request->getQuery('q'));
        $values['q'] = $q;
        if ($q !== '' && !empty($filter['search'])) {
            $or = [];
            foreach ($filter['search'] as $col) {
                $or[$alias . '.' . $col . ' LIKE'] = '%' . $q . '%';
            }
            $query->where(['OR' => $or]);
        }
        $from = trim((string)$this->request->getQuery('from'));
        $to = trim((string)$this->request->getQuery('to'));
        $values['from'] = $from;
        $values['to'] = $to;
        $dateCol = $filter['dates'] ?? null;
        if ($dateCol && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
            $query->where([$alias . '.' . $dateCol . ' >=' => $from]);
        }
        if ($dateCol && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $query->where([$alias . '.' . $dateCol . ' <=' => $to . ' 23:59:59']);
        }
        if ($this->request->getQuery('export') === 'csv') {
            $handle = fopen('php://temp', 'w+');
            fputcsv($handle, $header);
            foreach ($query->limit(5000)->all() as $row) {
                fputcsv($handle, $map($row));
            }
            rewind($handle);
            $csv = stream_get_contents($handle) ?: '';
            fclose($handle);

            return $this->response->withType('csv')->withDownload($file)->withStringBody($csv);
        }
        $this->paginate = ['limit' => 20, 'maxLimit' => 100];
        $items = $this->paginate($query);
        if (!empty($filter['readonly'])) {
            $this->set('canEdit', false);
        }
        $this->set('pageTitle', $title);
        $this->set(compact('title', 'header', 'items', 'map', 'filter', 'values', 'addAction'));
        $this->set('showDates', !empty($dateCol));
        $this->set('allowDelete', $filter['allowDelete'] ?? true);
        $this->set('listAction', $selfAction);
        if ($extra) {
            $extra();
        }
        $this->render('/Admin/Iso/record');

        return null;
    }

    private function renderForm(string $title, string $alias, string $listAction, $id, array $fields, ?callable $mutate = null, ?callable $after = null): ?Response
    {
        $table = $this->fetchTable($alias);
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        if ($this->request->is(['post', 'put'])) {
            $data = [];
            foreach ($fields as $key => $field) {
                $value = $this->request->getData($key);
                $value = is_string($value) ? trim($value) : $value;
                if ($value === '' && ($field['type'] ?? 'text') === 'select') {
                    $value = null;
                }
                $data[$key] = $value;
            }
            $error = null;
            foreach ($fields as $key => $field) {
                if (!empty($field['required']) && ($data[$key] ?? '') === '') {
                    $error = $field['label'] . ' is required.';
                    break;
                }
                $options = $field['options'] ?? null;
                if (is_array($options) && ($data[$key] ?? null) !== null && !array_key_exists((string)$data[$key], $options)) {
                    $error = $field['label'] . ' has an unknown value.';
                    break;
                }
            }
            if ($error === null && $mutate) {
                $result = $mutate($table, $data, $id ? $entity : null);
                if (is_string($result)) {
                    $error = $result;
                } else {
                    $data = $result;
                }
            }
            if ($error !== null) {
                $this->Flash->error($error);
                $entity = $table->patchEntity($entity, $data);
            } else {
                $isNew = $entity->isNew();
                if ($isNew) {
                    $data['created_by'] = $this->adminId();
                    $data['created'] = date('Y-m-d H:i:s');
                }
                $data['modified'] = date('Y-m-d H:i:s');
                $entity = $table->patchEntity($entity, $data);
                if ($table->save($entity)) {
                    if ($after) {
                        $after($entity);
                    }
                    $this->audit($isNew ? 'create' : 'update', $alias, (int)$entity->id, $title . ': ' . ($entity->code ?? $entity->name ?? ''));
                    $this->Flash->success($title . ' saved.');

                    return $this->redirect(['action' => $listAction]);
                }
                $this->Flash->error('Could not save this record.');
            }
        }
        $this->set('pageTitle', $title);
        $this->set(compact('title', 'fields', 'entity', 'listAction'));
        $this->render('/Admin/Iso/form');

        return null;
    }

    private function audit(string $action, string $entity, ?int $entityId, string $summary): void
    {
        $table = $this->fetchTable('ErpAuditLogs');
        $table->save($table->newEntity([
            'actor' => (string)($this->Session->read('User.username') ?: 'admin'),
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'summary' => mb_substr($summary, 0, 255),
            'created' => date('Y-m-d H:i:s'),
        ]));
    }

    private function erpRole(): ?string
    {
        if ($this->Session->read('is_admin')) {
            return 'super_admin';
        }
        $role = (string)$this->Session->read('iso_role');

        return isset(IsoAccess::ROLES[$role]) ? $role : null;
    }

    private function sectionFor(string $action): string
    {
        $map = [
            'index' => 'overview',
            'partners' => 'partners',
            'partnerForm' => 'partners',
            'units' => 'admin',
            'unitForm' => 'admin',
            'godowns' => 'admin',
            'godownForm' => 'admin',
            'settings' => 'admin',
            'auditLog' => 'admin',
            'purchasing' => 'purchase',
            'requests' => 'purchase',
            'requestForm' => 'purchase',
            'quotations' => 'purchase',
            'quotationForm' => 'purchase',
            'orders' => 'purchase',
            'orderForm' => 'purchase',
            'cancelOrder' => 'purchase',
            'goodsReceipts' => 'purchase',
            'goodsReceiptForm' => 'purchase',
            'bills' => 'purchase',
            'billForm' => 'purchase',
            'approveRequest' => 'purchase_approve',
            'approveOrder' => 'purchase_approve',
            'inventory' => 'inventory',
            'items' => 'inventory',
            'itemForm' => 'inventory',
            'transfer' => 'inventory',
            'adjustment' => 'inventory',
            'production' => 'production',
            'bomForm' => 'production',
            'productionForm' => 'production',
            'issueProduction' => 'production',
            'completeProduction' => 'production',
            'sales' => 'sales',
            'salesQuotations' => 'sales',
            'salesQuotationForm' => 'sales',
            'salesOrders' => 'sales',
            'salesOrderForm' => 'sales',
            'approveSalesOrder' => 'sales_approve',
            'deliveries' => 'sales',
            'deliveryForm' => 'sales',
            'salesInvoices' => 'sales',
            'salesInvoiceForm' => 'sales',
            'outstanding' => 'sales',
            'banking' => 'banking',
            'payments' => 'banking',
            'paymentForm' => 'banking',
            'reconcile' => 'banking',
            'financials' => 'financials',
            'trialBalance' => 'financials',
            'profitLoss' => 'financials',
            'balanceSheet' => 'financials',
            'gstSummary' => 'financials',
            'lockPeriod' => 'financials',
            'openingForm' => 'financials',
        ];

        return $map[$action] ?? 'overview';
    }

    private function adminId(): ?int
    {
        $id = $this->Session->read('User.id');

        return $id ? (int)$id : null;
    }
}
