<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class DocumentsController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Documents');
        $employeeId = $this->request->getQuery('employee_id');
        $query = $this->fetchTable('HrDocuments')->find()
            ->contain(['HrEmployees']);
        if ($employeeId) {
            $query->where(['employee_id' => (int)$employeeId]);
        }
        $this->hrPaginate($query, [
            'order' => ['HrDocuments.id' => 'DESC'],
            'sortableFields' => ['HrDocuments.id', 'HrDocuments.doc_type', 'HrDocuments.created'],
        ]);
        $employees = $this->fetchTable('HrEmployees')->find('list', [
            'keyField' => 'id',
            'valueField' => 'full_name',
        ])->where(['status' => 'active'])->toArray();
        $this->set(compact('employees', 'employeeId'));
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Upload Document');
        $employees = $this->fetchTable('HrEmployees')->find('list', [
            'keyField' => 'id',
            'valueField' => 'full_name',
        ])->where(['status' => 'active'])->toArray();

        if ($this->request->is('post')) {
            $file = $this->request->getData('file');
            $relPath = '';
            if ($file && is_object($file) && method_exists($file, 'getError') && $file->getError() === UPLOAD_ERR_OK) {
                $dir = WWW_ROOT . 'uploads' . DS . 'hrms' . DS . 'docs' . DS;
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientFilename());
                $file->moveTo($dir . $name);
                $relPath = 'uploads/hrms/docs/' . $name;
            }
            if ($relPath === '') {
                $this->Flash->error('Please upload a file.');
            } else {
                $entity = $this->fetchTable('HrDocuments')->newEntity([
                    'employee_id' => (int)$this->request->getData('employee_id'),
                    'doc_type' => (string)$this->request->getData('doc_type'),
                    'title' => (string)$this->request->getData('title'),
                    'file_path' => $relPath,
                    'uploaded_by' => (int)$this->Session->read('HrUser.id'),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ]);
                if ($this->fetchTable('HrDocuments')->save($entity)) {
                    $this->Flash->success('Document uploaded.');
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error('Save failed.');
            }
        }
        $this->set(compact('employees'));
    }
}
