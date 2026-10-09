<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Utility\IsoAccess;
use Cake\Event\EventInterface;
use Cake\Http\Response;

class IsoController extends AppController
{
    public const DEPARTMENTS = [
        'Management' => 'Management',
        'Purchase & Admin' => 'Purchase & Admin',
        'QA & QC' => 'QA & QC',
        'Maintenance' => 'Maintenance',
        'Production' => 'Production',
    ];

    public const PEOPLE = [
        'Mr Kal',
        'Parthiban Kumar',
        'Jaswinder',
        'Parminder',
        'Rohit',
        'Manohar',
        'Production Supervisor',
        'Karan',
        'Sikkandhar',
    ];

    public const DOC_TYPES = [
        'sop' => 'SOP',
        'work_instruction' => 'Work instruction',
        'form' => 'Form',
        'record' => 'Record',
    ];

    public const DOC_STATUSES = [
        'draft' => 'Draft',
        'in_review' => 'In review',
        'approved' => 'Approved',
        'obsolete' => 'Obsolete',
    ];

    public const MACHINES = [
        'extruder' => 'Main machine & Extruder',
        'sausage_cartridge' => 'Sausage and Cartridge',
    ];

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
        $this->viewBuilder()->setLayout('admin_layout');
        $this->viewBuilder()->addHelper('Paginator');
        $this->viewBuilder()->addHelper('Form');
        $role = $this->isoRole();
        if ($role === null) {
            $this->Flash->error('You need to be logged in to access this area.');

            return $this->redirect('/admin');
        }
        $section = $this->sectionFor((string)$this->request->getParam('action'));
        if (!IsoAccess::canView($role, $section)) {
            $this->Flash->error('Your role cannot open that ISO screen.');

            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is(['post', 'put']) && !IsoAccess::canWrite($role, $section)) {
            $this->Flash->error('Your role can view this screen but cannot change it.');

            return $this->redirect(['action' => $this->request->getParam('action')]);
        }
        $this->set('title_for_layout', 'ISO 9001');
        $this->set('isoRole', $role);
        $this->set('isoRoleLabel', IsoAccess::ROLES[$role] ?? $role);
        $this->set('canEdit', IsoAccess::canWrite($role, $section));
        $names = [];
        foreach ($this->fetchTable('HrEmployees')->find()->where(['status' => 'active'])->orderBy(['full_name' => 'ASC'])->limit(200) as $person) {
            $names[] = (string)$person->full_name;
        }
        $this->set('isoPeople', $names !== [] ? $names : self::PEOPLE);
    }

    public function index(): void
    {
        $this->set('pageTitle', 'ISO 9001');
        $role = $this->isoRole() ?? '';
        $cards = [
            ['label' => 'Documents waiting approval', 'count' => $this->countWhere('IsoDocuments', ['status' => 'in_review']), 'url' => 'documents?status=in_review', 'color' => '#438eb9'],
            ['label' => 'Incoming awaiting QA', 'count' => $this->countWhere('IsoReceipts', ['inspection_result' => 'pending']), 'url' => 'receipts?inspection_result=pending', 'color' => '#F79263'],
            ['label' => 'Open non-conformances', 'count' => $this->countWhere('IsoNonconformances', ['status' => 'open']), 'url' => 'nonconformances?status=open', 'color' => '#CC5D5E'],
            ['label' => 'Overdue corrective actions', 'count' => $this->overdueActions(), 'url' => 'corrective?status=open', 'color' => '#AEC95B'],
        ];
        $sectionOf = ['documents' => 'documents', 'receipts' => 'receipts', 'nonconformances' => 'ncr', 'corrective' => 'corrective'];
        $cards = array_values(array_filter($cards, function (array $card) use ($role, $sectionOf): bool {
            $path = strtok($card['url'], '?') ?: '';

            return IsoAccess::canView($role, $sectionOf[$path] ?? 'overview');
        }));
        $this->set('cards', $cards);
    }

    public function documents(): ?Response
    {
        return $this->renderList('Documents', 'iso-documents.csv', 'IsoDocuments', [
            'Doc no', 'Title', 'Document Category', 'Department', 'Revision', 'Status', 'Approved by',
        ], [
            'selects' => [
                'doc_type' => $this->documentCategories(),
                'status' => self::DOC_STATUSES,
                'department' => $this->departmentOptions(),
            ],
            'search' => ['doc_no', 'title'],
        ], function ($row) {
            return [
                $row->doc_no,
                $row->title,
                self::DOC_TYPES[$row->doc_type] ?? $row->doc_type,
                $row->department,
                $row->revision,
                $row->status,
                $row->approved_by,
            ];
        }, 'documentForm', 'documents');
    }

    public function documentForm($id = null): ?Response
    {
        return $this->renderForm('Document', 'IsoDocuments', 'documents', $id, [
            'doc_no' => ['label' => 'Document number', 'required' => true, 'placeholder' => 'e.g. SOP-QA-001'],
            'title' => ['label' => 'Title', 'required' => true, 'placeholder' => 'Short name of this document'],
            'doc_type' => ['label' => 'Document Category', 'type' => 'select_other', 'required' => true, 'options' => $this->documentCategories(), 'placeholder' => 'Select a document category', 'other_label' => 'Other — add new category', 'other_placeholder' => 'Type the new document category'],
            'department' => ['label' => 'Department', 'type' => 'select', 'options' => $this->departmentOptions(), 'placeholder' => 'Select the department that owns it'],
            'revision' => ['label' => 'Revision', 'default' => '1', 'placeholder' => 'e.g. 1 or 1.1'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => self::DOC_STATUSES, 'placeholder' => 'Select draft, in review, approved, or obsolete'],
            'approved_by' => ['label' => 'Approved by', 'type' => 'person', 'placeholder' => 'Select who approved it'],
            'approved_at' => ['label' => 'Approved on', 'type' => 'date'],
            'file_path' => ['label' => 'File', 'type' => 'file'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'What this document is used for'],
        ], function ($table, array $data, $existing) {
            if ($this->isoRole() === 'document_controller' && in_array((string)($data['status'] ?? ''), ['approved', 'obsolete'], true)) {
                $data['status'] = 'in_review';
            }
            if ($existing && (string)$existing->revision !== (string)($data['revision'] ?? '')) {
                $rev = $this->fetchTable('IsoDocumentRevisions')->newEntity([
                    'document_id' => $existing->id,
                    'doc_no' => $existing->doc_no,
                    'title' => $existing->title,
                    'revision' => $existing->revision,
                    'status' => $existing->status,
                    'file_path' => $existing->file_path,
                    'notes' => $existing->notes,
                    'created_by' => $this->adminId(),
                    'created' => date('Y-m-d H:i:s'),
                ]);
                $this->fetchTable('IsoDocumentRevisions')->save($rev);
            }
            if ($existing && empty($data['file_path'])) {
                unset($data['file_path']);
            }

            return $data;
        }, function ($id) {
            if (!$id) {
                $this->set('revisions', []);

                return;
            }
            $this->set('revisions', $this->fetchTable('IsoDocumentRevisions')->find()
                ->where(['document_id' => (int)$id])
                ->orderBy(['id' => 'DESC'])
                ->all());
        });
    }

    public function training(): ?Response
    {
        return $this->renderList('Training', 'iso-training.csv', 'IsoTraining', [
            'Person', 'Department', 'Trainer', 'Date', 'Document',
        ], [
            'selects' => ['department' => $this->departmentOptions()],
            'search' => ['person_name', 'trainer_name'],
        ], function ($row) {
            $doc = $row->document_id ? $this->labelOf('IsoDocuments', (int)$row->document_id, 'doc_no') : '';

            return [$row->person_name, $row->department, $row->trainer_name, $this->plainDate($row->trained_on), $doc];
        }, 'trainingForm', 'training');
    }

    public function trainingForm($id = null): ?Response
    {
        return $this->renderForm('Training record', 'IsoTraining', 'training', $id, [
            'person_name' => ['label' => 'Person', 'type' => 'person', 'required' => true, 'placeholder' => 'Select the person who was trained'],
            'department' => ['label' => 'Department', 'type' => 'select', 'options' => $this->departmentOptions(), 'placeholder' => 'Select their department'],
            'document_id' => ['label' => 'Document', 'type' => 'select', 'options' => $this->optionList('IsoDocuments', 'doc_no', 'title'), 'placeholder' => 'Select the document they were trained on'],
            'trainer_name' => ['label' => 'Trainer', 'type' => 'person', 'placeholder' => 'Select who gave the training'],
            'trained_on' => ['label' => 'Trained on', 'type' => 'date'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'What was covered in the training'],
        ]);
    }

    public function suppliers(): ?Response
    {
        return $this->renderList('Suppliers', 'iso-suppliers.csv', 'IsoSuppliers', [
            'Supplier', 'Material', 'Contact', 'Phone', 'Status',
        ], [
            'selects' => ['status' => ['approved' => 'Approved', 'blocked' => 'Blocked']],
            'search' => ['name', 'material', 'contact'],
        ], function ($row) {
            return [$row->name, $row->material, $row->contact, $row->phone, $row->status];
        }, 'supplierForm', 'suppliers');
    }

    public function supplierForm($id = null): ?Response
    {
        return $this->renderForm('Supplier', 'IsoSuppliers', 'suppliers', $id, [
            'name' => ['label' => 'Supplier name', 'required' => true, 'placeholder' => 'Company name, e.g. Focal Point Chemicals'],
            'material' => ['label' => 'Material supplied', 'placeholder' => 'What they supply, e.g. polymer resin'],
            'contact' => ['label' => 'Contact', 'placeholder' => 'Contact person name'],
            'phone' => ['label' => 'Phone', 'placeholder' => 'Mobile or landline'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['approved' => 'Approved', 'blocked' => 'Blocked'], 'placeholder' => 'Approved suppliers can be used for purchase'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Approval notes or why they are blocked'],
        ]);
    }

    public function materials(): ?Response
    {
        return $this->renderList('Materials', 'iso-materials.csv', 'IsoMaterials', [
            'Material', 'Supplier', 'Spec', 'Status',
        ], [
            'selects' => ['status' => ['active' => 'Active', 'inactive' => 'Inactive']],
            'search' => ['name', 'spec'],
        ], function ($row) {
            return [$row->name, $this->labelOf('IsoSuppliers', (int)$row->supplier_id, 'name'), $row->spec, $row->status];
        }, 'materialForm', 'materials');
    }

    public function materialForm($id = null): ?Response
    {
        return $this->renderForm('Material', 'IsoMaterials', 'materials', $id, [
            'name' => ['label' => 'Material', 'required' => true, 'placeholder' => 'Material name, e.g. Polymer resin'],
            'supplier_id' => ['label' => 'Supplier', 'type' => 'select', 'options' => $this->optionList('IsoSuppliers', 'name'), 'placeholder' => 'Select the approved supplier'],
            'spec' => ['label' => 'Specification', 'placeholder' => 'Grade, size, or acceptance spec'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Active', 'inactive' => 'Inactive'], 'placeholder' => 'Inactive materials are not used for new receipts'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Storage or handling notes'],
        ]);
    }

    public function receipts(): ?Response
    {
        return $this->renderList('Incoming material', 'iso-receipts.csv', 'IsoReceipts', [
            'Receipt', 'Material', 'Lot', 'Qty', 'Received', 'Received by', 'QA result',
        ], [
            'selects' => ['inspection_result' => ['pending' => 'Pending', 'pass' => 'Pass', 'fail' => 'Fail']],
            'search' => ['receipt_no', 'material', 'lot_no', 'received_by'],
            'dates' => 'received_on',
        ], function ($row) {
            return [
                $row->receipt_no,
                $row->material,
                $row->lot_no,
                $row->quantity,
                $this->plainDate($row->received_on),
                $row->received_by,
                $row->inspection_result,
            ];
        }, 'receiptForm', 'receipts');
    }

    public function receiptForm($id = null): ?Response
    {
        return $this->renderForm('Incoming material', 'IsoReceipts', 'receipts', $id, [
            'receipt_no' => ['label' => 'Receipt no', 'default' => $id ? null : $this->nextNo('IsoReceipts', 'receipt_no', 'ISO-RCV'), 'placeholder' => 'Filled automatically, change only if needed'],
            'supplier_id' => ['label' => 'Supplier', 'type' => 'select', 'options' => $this->optionList('IsoSuppliers', 'name'), 'placeholder' => 'Select who sent this material'],
            'material' => ['label' => 'Material', 'required' => true, 'placeholder' => 'Material received, e.g. Polymer resin'],
            'lot_no' => ['label' => 'Lot / batch', 'placeholder' => 'Supplier lot or batch number'],
            'quantity' => ['label' => 'Quantity', 'placeholder' => 'e.g. 25 kg'],
            'received_on' => ['label' => 'Received on', 'type' => 'date'],
            'received_by' => ['label' => 'Received by', 'type' => 'person', 'placeholder' => 'Select who received it'],
            'inspection_result' => ['label' => 'QA result', 'type' => 'select', 'options' => ['pending' => 'Pending', 'pass' => 'Pass', 'fail' => 'Fail'], 'placeholder' => 'Fail opens a non-conformance'],
            'inspected_by' => ['label' => 'Inspected by', 'type' => 'person', 'placeholder' => 'Select the QA inspector'],
            'inspected_on' => ['label' => 'Inspected on', 'type' => 'date'],
            'remarks' => ['label' => 'Remarks', 'type' => 'textarea', 'placeholder' => 'What QA found, e.g. viscosity out of spec'],
        ], null, null, function ($entity) {
            $this->linkFailure($entity, 'IsoReceipts', 'inspection_result', 'incoming', (string)$entity->remarks, null, null);
        });
    }

    public function batches(): ?Response
    {
        return $this->renderList('Production batches', 'iso-batches.csv', 'IsoBatches', [
            'Batch', 'Product', 'Machine', 'Operators', 'Supervisor', 'Started', 'Ended',
        ], [
            'selects' => ['machine' => $this->machineOptions()],
            'search' => ['batch_no', 'product', 'operator_names', 'supervisor_name'],
            'dates' => 'started_on',
        ], function ($row) {
            return [
                $row->batch_no,
                $row->product,
                self::MACHINES[$row->machine] ?? $row->machine,
                $row->operator_names,
                $row->supervisor_name,
                $this->plainDate($row->started_on),
                $this->plainDate($row->ended_on),
            ];
        }, 'batchForm', 'batches');
    }

    public function batchForm($id = null): ?Response
    {
        return $this->renderForm('Production batch', 'IsoBatches', 'batches', $id, [
            'batch_no' => ['label' => 'Batch no', 'default' => $id ? null : $this->nextNo('IsoBatches', 'batch_no', 'ISO-BAT'), 'placeholder' => 'Filled automatically, change only if needed'],
            'product' => ['label' => 'Product', 'required' => true, 'placeholder' => 'Product being made'],
            'machine' => ['label' => 'Machine', 'type' => 'select_other', 'store' => 'machine', 'options' => $this->machineOptions(), 'placeholder' => 'Select the machine, or add a new one', 'other_label' => 'Other — add new machine', 'other_placeholder' => 'Type the new machine name'],
            'operator_names' => ['label' => 'Operators', 'placeholder' => 'Names of operators on this batch'],
            'supervisor_name' => ['label' => 'Supervisor', 'type' => 'person', 'placeholder' => 'Select the production supervisor'],
            'started_on' => ['label' => 'Started', 'type' => 'date'],
            'ended_on' => ['label' => 'Ended', 'type' => 'date'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Anything unusual about this batch'],
        ]);
    }

    public function inspections(): ?Response
    {
        return $this->renderList('QC inspections', 'iso-inspections.csv', 'IsoInspections', [
            'Inspection', 'Stage', 'Result', 'Inspected by', 'Date', 'Remarks',
        ], [
            'selects' => [
                'stage' => ['incoming' => 'Incoming', 'in_process' => 'In process', 'final' => 'Final'],
                'result' => ['pass' => 'Pass', 'fail' => 'Fail'],
            ],
            'search' => ['inspection_no', 'inspected_by', 'remarks'],
            'dates' => 'inspected_on',
        ], function ($row) {
            return [
                $row->inspection_no,
                str_replace('_', ' ', (string)$row->stage),
                $row->result,
                $row->inspected_by,
                $this->plainDate($row->inspected_on),
                $row->remarks,
            ];
        }, 'inspectionForm', 'inspections');
    }

    public function inspectionForm($id = null): ?Response
    {
        return $this->renderForm('QC inspection', 'IsoInspections', 'inspections', $id, [
            'inspection_no' => ['label' => 'Inspection no', 'default' => $id ? null : $this->nextNo('IsoInspections', 'inspection_no', 'ISO-INS'), 'placeholder' => 'Filled automatically, change only if needed'],
            'stage' => ['label' => 'Stage', 'type' => 'select', 'options' => ['incoming' => 'Incoming', 'in_process' => 'In process', 'final' => 'Final'], 'placeholder' => 'Incoming, in process, or final QC'],
            'receipt_id' => ['label' => 'Incoming receipt', 'type' => 'select', 'options' => $this->optionList('IsoReceipts', 'receipt_no', 'material'), 'placeholder' => 'Link the incoming material, if this is incoming QC'],
            'batch_id' => ['label' => 'Production batch', 'type' => 'select', 'options' => $this->optionList('IsoBatches', 'batch_no', 'product'), 'placeholder' => 'Link the batch, if this is in-process or final QC'],
            'result' => ['label' => 'Result', 'type' => 'select', 'options' => ['pass' => 'Pass', 'fail' => 'Fail'], 'placeholder' => 'Fail opens a non-conformance'],
            'inspected_by' => ['label' => 'Inspected by', 'type' => 'person', 'placeholder' => 'Select the inspector'],
            'inspected_on' => ['label' => 'Date', 'type' => 'date'],
            'remarks' => ['label' => 'Remarks', 'type' => 'textarea', 'placeholder' => 'What was checked and any defect found'],
        ], null, null, function ($entity) {
            $this->linkFailure($entity, 'IsoInspections', 'result', (string)$entity->stage, (string)$entity->remarks, $entity->receipt_id ? (int)$entity->receipt_id : null, $entity->batch_id ? (int)$entity->batch_id : null);
        });
    }

    public function nonconformances(): ?Response
    {
        return $this->renderList('Non-conformance', 'iso-nonconformances.csv', 'IsoNonconformances', [
            'NCR', 'Source', 'Department', 'Description', 'Status',
        ], [
            'selects' => [
                'status' => ['open' => 'Open', 'under_action' => 'Under action', 'closed' => 'Closed'],
                'source' => ['incoming' => 'Incoming', 'in_process' => 'In process', 'final' => 'Final', 'other' => 'Other'],
                'department' => $this->departmentOptions(),
            ],
            'search' => ['ncr_no', 'description'],
        ], function ($row) {
            return [$row->ncr_no, str_replace('_', ' ', (string)$row->source), $row->department, $row->description, $row->status];
        }, 'ncrForm', 'nonconformances');
    }

    public function ncrForm($id = null): ?Response
    {
        return $this->renderForm('Non-conformance', 'IsoNonconformances', 'nonconformances', $id, [
            'ncr_no' => ['label' => 'NCR no', 'default' => $id ? null : $this->nextNo('IsoNonconformances', 'ncr_no', 'ISO-NCR'), 'placeholder' => 'Filled automatically, change only if needed'],
            'source' => ['label' => 'Source', 'type' => 'select', 'options' => ['incoming' => 'Incoming', 'in_process' => 'In process', 'final' => 'Final', 'other' => 'Other'], 'placeholder' => 'Where the failure was found'],
            'department' => ['label' => 'Department', 'type' => 'select', 'options' => $this->departmentOptions(), 'placeholder' => 'Select the department'],
            'receipt_id' => ['label' => 'Incoming receipt', 'type' => 'select', 'options' => $this->optionList('IsoReceipts', 'receipt_no', 'material'), 'placeholder' => 'Link the failed receipt, if any'],
            'batch_id' => ['label' => 'Production batch', 'type' => 'select', 'options' => $this->optionList('IsoBatches', 'batch_no', 'product'), 'placeholder' => 'Link the failed batch, if any'],
            'description' => ['label' => 'What failed', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Describe the failure in plain words'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['open' => 'Open', 'under_action' => 'Under action', 'closed' => 'Closed'], 'placeholder' => 'Open until the corrective action is finished'],
        ]);
    }

    public function corrective(): ?Response
    {
        return $this->renderList('Corrective action', 'iso-corrective.csv', 'IsoActions', [
            'Action', 'NCR', 'Owner', 'Due', 'Status', 'Cause',
        ], [
            'selects' => ['status' => ['open' => 'Open', 'done' => 'Done', 'closed' => 'Closed']],
            'search' => ['action_no', 'owner_name', 'cause', 'action_text'],
            'dates' => 'due_date',
        ], function ($row) {
            return [
                $row->action_no,
                $this->labelOf('IsoNonconformances', (int)$row->nonconformance_id, 'ncr_no'),
                $row->owner_name,
                $this->plainDate($row->due_date),
                $row->status,
                $row->cause,
            ];
        }, 'correctiveForm', 'corrective');
    }

    public function correctiveForm($id = null): ?Response
    {
        return $this->renderForm('Corrective action', 'IsoActions', 'corrective', $id, [
            'action_no' => ['label' => 'Action no', 'default' => $id ? null : $this->nextNo('IsoActions', 'action_no', 'ISO-CAP'), 'placeholder' => 'Filled automatically, change only if needed'],
            'nonconformance_id' => ['label' => 'Non-conformance', 'type' => 'select', 'options' => $this->optionList('IsoNonconformances', 'ncr_no'), 'placeholder' => 'Select the NCR this action fixes'],
            'cause' => ['label' => 'Cause', 'type' => 'textarea', 'placeholder' => 'Why it happened'],
            'action_text' => ['label' => 'Action', 'type' => 'textarea', 'required' => true, 'placeholder' => 'What will be done to stop it happening again'],
            'owner_name' => ['label' => 'Owner', 'type' => 'person', 'placeholder' => 'Select who is responsible'],
            'due_date' => ['label' => 'Due date', 'type' => 'date'],
            'evidence' => ['label' => 'Evidence', 'type' => 'textarea', 'placeholder' => 'How you will show the action is done'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['open' => 'Open', 'done' => 'Done', 'closed' => 'Closed'], 'placeholder' => 'Open, done, or closed'],
            'closed_by' => ['label' => 'Closed by', 'type' => 'person', 'placeholder' => 'Select who closed it'],
            'closed_on' => ['label' => 'Closed on', 'type' => 'date'],
        ], function ($table, array $data, $existing) {
            if (($data['status'] ?? '') === 'closed' && empty($data['closed_on'])) {
                $data['closed_on'] = date('Y-m-d');
            }

            return $data;
        });
    }

    public function maintenance(): ?Response
    {
        return $this->renderList('Maintenance', 'iso-maintenance.csv', 'IsoMaintenance', [
            'Machine', 'Work done', 'Done on', 'Next due', 'Done by',
        ], [
            'selects' => ['machine' => $this->machineOptions()],
            'search' => ['work_done', 'done_by'],
            'dates' => 'done_on',
        ], function ($row) {
            return [
                self::MACHINES[$row->machine] ?? $row->machine,
                $row->work_done,
                $this->plainDate($row->done_on),
                $this->plainDate($row->next_due),
                $row->done_by,
            ];
        }, 'maintenanceForm', 'maintenance');
    }

    public function maintenanceForm($id = null): ?Response
    {
        return $this->renderForm('Maintenance', 'IsoMaintenance', 'maintenance', $id, [
            'machine' => ['label' => 'Machine', 'type' => 'select_other', 'store' => 'machine', 'options' => $this->machineOptions(), 'required' => true, 'placeholder' => 'Select the machine, or add a new one', 'other_label' => 'Other — add new machine', 'other_placeholder' => 'Type the new machine name'],
            'work_done' => ['label' => 'Work done', 'type' => 'textarea', 'required' => true, 'placeholder' => 'What was serviced or repaired'],
            'done_on' => ['label' => 'Done on', 'type' => 'date'],
            'next_due' => ['label' => 'Next due', 'type' => 'date'],
            'done_by' => ['label' => 'Done by', 'type' => 'person', 'placeholder' => 'Select who did the work'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Parts used or follow-up needed'],
        ]);
    }

    public function audits(): ?Response
    {
        return $this->renderList('Internal audit', 'iso-audits.csv', 'IsoAudits', [
            'Audit', 'Title', 'Planned', 'Auditor', 'Status', 'Findings',
        ], [
            'selects' => ['status' => ['planned' => 'Planned', 'done' => 'Done']],
            'search' => ['audit_no', 'title', 'auditor_name', 'findings'],
        ], function ($row) {
            return [$row->audit_no, $row->title, $this->plainDate($row->planned_on), $row->auditor_name, $row->status, $row->findings];
        }, 'auditForm', 'audits');
    }

    public function auditForm($id = null): ?Response
    {
        return $this->renderForm('Internal audit', 'IsoAudits', 'audits', $id, [
            'audit_no' => ['label' => 'Audit no', 'default' => $id ? null : $this->nextNo('IsoAudits', 'audit_no', 'ISO-AUD'), 'placeholder' => 'Filled automatically, change only if needed'],
            'title' => ['label' => 'Title', 'required' => true, 'placeholder' => 'What is being audited, e.g. Incoming inspection'],
            'planned_on' => ['label' => 'Planned on', 'type' => 'date'],
            'auditor_name' => ['label' => 'Auditor', 'type' => 'person', 'placeholder' => 'Select the auditor'],
            'findings' => ['label' => 'Findings', 'type' => 'textarea', 'placeholder' => 'What the auditor found'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['planned' => 'Planned', 'done' => 'Done'], 'placeholder' => 'Planned until the audit is finished'],
            'nonconformance_id' => ['label' => 'Linked non-conformance', 'type' => 'select', 'options' => $this->optionList('IsoNonconformances', 'ncr_no'), 'placeholder' => 'Link an NCR if the audit found a failure'],
        ]);
    }

    public function complaints(): ?Response
    {
        return $this->renderList('Complaints', 'iso-complaints.csv', 'IsoComplaints', [
            'Complaint', 'Customer', 'Product', 'Status', 'Description',
        ], [
            'selects' => ['status' => ['open' => 'Open', 'closed' => 'Closed']],
            'search' => ['complaint_no', 'customer_name', 'product', 'description'],
        ], function ($row) {
            return [$row->complaint_no, $row->customer_name, $row->product, $row->status, $row->description];
        }, 'complaintForm', 'complaints');
    }

    public function complaintForm($id = null): ?Response
    {
        return $this->renderForm('Complaint', 'IsoComplaints', 'complaints', $id, [
            'complaint_no' => ['label' => 'Complaint no', 'default' => $id ? null : $this->nextNo('IsoComplaints', 'complaint_no', 'ISO-CMP'), 'placeholder' => 'Filled automatically, change only if needed'],
            'customer_name' => ['label' => 'Customer', 'required' => true, 'placeholder' => 'Customer or company name'],
            'product' => ['label' => 'Product', 'placeholder' => 'Product they complained about'],
            'batch_id' => ['label' => 'Production batch', 'type' => 'select', 'options' => $this->optionList('IsoBatches', 'batch_no', 'product'), 'placeholder' => 'Link the batch, if known'],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'required' => true, 'placeholder' => 'What the customer reported'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['open' => 'Open', 'closed' => 'Closed'], 'placeholder' => 'Open until the complaint is resolved'],
            'nonconformance_id' => ['label' => 'Linked non-conformance', 'type' => 'select', 'options' => $this->optionList('IsoNonconformances', 'ncr_no'), 'placeholder' => 'Link an NCR if this needs corrective action'],
        ]);
    }

    public function reviews(): ?Response
    {
        return $this->renderList('Management review', 'iso-reviews.csv', 'IsoReviews', [
            'Meeting', 'Attendees', 'Decisions',
        ], [
            'search' => ['attendees', 'decisions'],
            'dates' => 'meeting_on',
        ], function ($row) {
            return [$this->plainDate($row->meeting_on), $row->attendees, $row->decisions];
        }, 'reviewForm', 'reviews');
    }

    public function reviewForm($id = null): ?Response
    {
        return $this->renderForm('Management review', 'IsoReviews', 'reviews', $id, [
            'meeting_on' => ['label' => 'Meeting date', 'type' => 'date', 'required' => true],
            'attendees' => ['label' => 'Attendees', 'placeholder' => 'Names of people in the meeting'],
            'decisions' => ['label' => 'Decisions', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Decisions and actions agreed'],
        ]);
    }

    private function renderList(string $title, string $file, string $alias, array $header, array $filter, callable $map, string $addAction, string $selfAction): ?Response
    {
        if ($this->request->is('post') && $this->request->getData('delete_id')) {
            $rowId = (int)$this->request->getData('delete_id');
            $row = $this->fetchTable($alias)->find()->where(['id' => $rowId])->first();
            if ($row && $alias === 'IsoDocuments') {
                $this->fetchTable('IsoDocumentRevisions')->deleteAll(['document_id' => $rowId]);
            }
            if ($row && $this->fetchTable($alias)->delete($row)) {
                $this->Flash->success($title . ' deleted.');
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
            $rows = [];
            foreach ($query->limit(5000)->all() as $row) {
                $rows[] = $map($row);
            }

            return $this->csvResponse($file, $header, $rows);
        }

        $this->paginate = ['limit' => 20, 'maxLimit' => 100];
        $items = $this->paginate($query);
        $this->set('pageTitle', $title);
        $this->set(compact('title', 'header', 'items', 'map', 'filter', 'values', 'addAction'));
        $this->set('showDates', !empty($dateCol));
        $this->set('listAction', $selfAction);
        $this->render('record');

        return null;
    }

    private function renderForm(string $title, string $alias, string $listAction, $id, array $fields, ?callable $mutate = null, ?callable $extra = null, ?callable $after = null): ?Response
    {
        $table = $this->fetchTable($alias);
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        if ($this->request->is(['post', 'put'])) {
            $data = [];
            foreach ($fields as $key => $field) {
                $type = $field['type'] ?? 'text';
                if ($type === 'file') {
                    $stored = $this->storeUpload($key);
                    if ($stored !== null) {
                        $data[$key] = $stored;
                    }
                    continue;
                }
                if ($type === 'select_other') {
                    $value = trim((string)$this->request->getData($key));
                    if ($value === '__other__') {
                        $value = trim((string)$this->request->getData($key . '_other'));
                        if ($value !== '') {
                            if (($field['store'] ?? '') === 'machine') {
                                $this->saveMachine($value);
                            } else {
                                $this->saveDocumentCategory($value);
                            }
                        }
                    }
                    $data[$key] = $value === '' ? null : $value;
                    continue;
                }
                $value = $this->request->getData($key);
                if (is_string($value)) {
                    $value = trim($value);
                }
                if ($value === '' && ($type === 'select' || $type === 'date')) {
                    $value = null;
                }
                $data[$key] = $value;
            }
            foreach ($fields as $key => $field) {
                if (!empty($field['required']) && ($data[$key] ?? '') === '') {
                    $this->Flash->error($field['label'] . ' is required.');
                    $entity = $table->patchEntity($entity, $data);
                    $this->set(compact('title', 'fields', 'entity', 'listAction'));
                    $this->set('pageTitle', $title);
                    if ($extra) {
                        $extra($id);
                    }
                    $this->render('form');

                    return null;
                }
            }
            if ($mutate) {
                $data = $mutate($table, $data, $id ? $entity : null);
            }
            if ($entity->isNew()) {
                $data['created_by'] = $this->adminId();
                $data['created'] = date('Y-m-d H:i:s');
            }
            $data['modified'] = date('Y-m-d H:i:s');
            $entity = $table->patchEntity($entity, $data);
            if ($table->save($entity)) {
                if ($after) {
                    $after($entity);
                }
                $this->Flash->success($title . ' saved.');

                return $this->redirect(['action' => $listAction]);
            }
            $this->Flash->error('Could not save this record.');
        }
        $this->set('pageTitle', $title);
        $this->set(compact('title', 'fields', 'entity', 'listAction'));
        if ($extra) {
            $extra($id);
        }
        $this->render('form');

        return null;
    }

    private function linkFailure($entity, string $alias, string $resultField, string $source, string $description, ?int $receiptId, ?int $batchId): void
    {
        if ((string)$entity->{$resultField} !== 'fail' || !empty($entity->nonconformance_id)) {
            return;
        }
        $ncr = $this->fetchTable('IsoNonconformances')->newEntity([
            'ncr_no' => $this->nextNo('IsoNonconformances', 'ncr_no', 'ISO-NCR'),
            'source' => in_array($source, ['incoming', 'in_process', 'final'], true) ? $source : 'other',
            'department' => 'QA & QC',
            'receipt_id' => $receiptId,
            'batch_id' => $batchId,
            'description' => $description !== '' ? $description : 'Failed inspection',
            'status' => 'open',
            'created_by' => $this->adminId(),
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ]);
        if ($this->fetchTable('IsoNonconformances')->save($ncr)) {
            $entity->nonconformance_id = $ncr->id;
            $this->fetchTable($alias)->save($entity);
        }
    }

    private function storeUpload(string $field): ?string
    {
        $file = $this->request->getData($field);
        if (!is_object($file) || !method_exists($file, 'getError') || $file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }
        $dir = WWW_ROOT . 'files' . DIRECTORY_SEPARATOR . 'iso';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $safe = preg_replace('/[^A-Za-z0-9._-]/', '', (string)$file->getClientFilename()) ?: 'file';
        $name = date('YmdHis') . '_' . $safe;
        $file->moveTo($dir . DIRECTORY_SEPARATOR . $name);

        return 'files/iso/' . $name;
    }

    private function optionList(string $alias, string $left, ?string $right = null): array
    {
        $out = [];
        foreach ($this->fetchTable($alias)->find()->orderBy(['id' => 'DESC'])->limit(300) as $row) {
            $label = (string)$row->{$left};
            if ($right && !empty($row->{$right})) {
                $label .= ' — ' . $row->{$right};
            }
            $out[(string)$row->id] = $label;
        }

        return $out;
    }

    private function labelOf(string $alias, int $id, string $field): string
    {
        if ($id < 1) {
            return '';
        }
        try {
            $row = $this->fetchTable($alias)->get($id);
        } catch (\Throwable) {
            return '';
        }

        return (string)($row->{$field} ?? '');
    }

    private function countWhere(string $alias, array $where): int
    {
        return $this->fetchTable($alias)->find()->where($where)->count();
    }

    private function overdueActions(): int
    {
        return $this->fetchTable('IsoActions')->find()->where([
            'status' => 'open',
            'due_date <' => date('Y-m-d'),
            'due_date IS NOT' => null,
        ])->count();
    }

    private function nextNo(string $alias, string $field, string $prefix): string
    {
        $like = $prefix . '-' . date('Y') . '-%';
        $last = $this->fetchTable($alias)->find()
            ->select([$field])
            ->where([$field . ' LIKE' => $like])
            ->orderBy(['id' => 'DESC'])
            ->first();
        $n = 1;
        if ($last && preg_match('/(\d+)$/', (string)$last->{$field}, $m)) {
            $n = (int)$m[1] + 1;
        }

        return sprintf('%s-%s-%04d', $prefix, date('Y'), $n);
    }

    private function machineOptions(): array
    {
        $options = self::MACHINES;
        foreach ($this->fetchTable('IsoMachines')->find()->orderBy(['name' => 'ASC']) as $row) {
            $name = trim((string)$row->name);
            if ($name !== '' && !isset($options[$name]) && !in_array($name, $options, true)) {
                $options[$name] = $name;
            }
        }

        return $options;
    }

    private function saveMachine(string $name): void
    {
        $name = trim($name);
        if ($name === '' || in_array($name, self::MACHINES, true)) {
            return;
        }
        $table = $this->fetchTable('IsoMachines');
        if ($table->find()->where(['name' => $name])->first()) {
            return;
        }
        $table->save($table->newEntity([
            'name' => $name,
            'created' => date('Y-m-d H:i:s'),
        ]));
    }

    private function departmentOptions(): array
    {
        $options = [];
        foreach ($this->fetchTable('HrDepartments')->find()->orderBy(['name' => 'ASC']) as $row) {
            $name = trim((string)$row->name);
            if ($name !== '') {
                $options[$name] = $name;
            }
        }
        foreach (self::DEPARTMENTS as $name) {
            if (!isset($options[$name])) {
                $options[$name] = $name;
            }
        }

        return $options !== [] ? $options : self::DEPARTMENTS;
    }

    private function documentCategories(): array
    {
        $options = self::DOC_TYPES;
        foreach ($this->fetchTable('IsoDocumentCategories')->find()->orderBy(['name' => 'ASC']) as $row) {
            $name = trim((string)$row->name);
            if ($name !== '' && !isset($options[$name]) && !in_array($name, $options, true)) {
                $options[$name] = $name;
            }
        }

        return $options;
    }

    private function saveDocumentCategory(string $name): void
    {
        $name = trim($name);
        if ($name === '') {
            return;
        }
        $table = $this->fetchTable('IsoDocumentCategories');
        $exists = $table->find()->where(['name' => $name])->first();
        if ($exists) {
            return;
        }
        $table->save($table->newEntity([
            'name' => $name,
            'created' => date('Y-m-d H:i:s'),
        ]));
    }

    private function isoRole(): ?string
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
            'documents' => 'documents',
            'documentForm' => 'documents',
            'training' => 'training',
            'trainingForm' => 'training',
            'suppliers' => 'suppliers',
            'supplierForm' => 'suppliers',
            'materials' => 'materials',
            'materialForm' => 'materials',
            'receipts' => 'receipts',
            'receiptForm' => 'receipts',
            'batches' => 'batches',
            'batchForm' => 'batches',
            'inspections' => 'inspections',
            'inspectionForm' => 'inspections',
            'nonconformances' => 'ncr',
            'ncrForm' => 'ncr',
            'corrective' => 'corrective',
            'correctiveForm' => 'corrective',
            'maintenance' => 'maintenance',
            'maintenanceForm' => 'maintenance',
            'audits' => 'audits',
            'auditForm' => 'audits',
            'complaints' => 'complaints',
            'complaintForm' => 'complaints',
            'reviews' => 'reviews',
            'reviewForm' => 'reviews',
        ];

        return $map[$action] ?? 'overview';
    }

    private function adminId(): ?int
    {
        $id = $this->Session->read('User.id');

        return $id ? (int)$id : null;
    }

    private function plainDate(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        if (is_object($value) && method_exists($value, 'format')) {
            return $value->format('Y-m-d');
        }
        $ts = strtotime((string)$value);

        return $ts ? date('Y-m-d', $ts) : (string)$value;
    }

    private function csvResponse(string $filename, array $header, array $rows): Response
    {
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, $header);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $this->response
            ->withType('csv')
            ->withDownload($filename)
            ->withStringBody($csv);
    }
}
