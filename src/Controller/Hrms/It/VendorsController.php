<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class VendorsController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'Vendors');
        $q = trim((string)$this->request->getQuery('q'));
        $query = $this->fetchTable('ItVendors')->find();
        if ($q !== '') {
            $query->where([
                'OR' => [
                    'ItVendors.name LIKE' => '%' . $q . '%',
                    'ItVendors.contact_person LIKE' => '%' . $q . '%',
                    'ItVendors.phone LIKE' => '%' . $q . '%',
                    'ItVendors.gst_number LIKE' => '%' . $q . '%',
                ],
            ]);
        }
        $this->hrPaginate($query, [
            'order' => ['ItVendors.name' => 'ASC'],
            'sortableFields' => ['ItVendors.name', 'ItVendors.phone', 'ItVendors.id'],
        ]);
        $this->set(compact('q'));
    }

    public function add()
    {
        $this->set('pageTitle', 'Add Vendor');
        $entity = $this->fetchTable('ItVendors')->newEmptyEntity();
        if ($this->request->is('post') && $this->saveVendor($entity, true)) {
            return $this->redirect(['action' => 'view', $entity->id]);
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $entity = $this->fetchTable('ItVendors')->get($id);
        $this->set('pageTitle', 'Edit ' . $entity->name);
        if ($this->request->is(['post', 'put', 'patch']) && $this->saveVendor($entity, false)) {
            return $this->redirect(['action' => 'view', $entity->id]);
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function view($id = null)
    {
        $vendor = $this->fetchTable('ItVendors')->get($id, contain: [
            'ItAssets',
            'ItRepairs' => ['ItAssets'],
            'ItPurchaseRequests',
            'ItMaintenance' => ['ItAssets'],
        ]);
        $this->set('pageTitle', $vendor->name);
        $this->set(compact('vendor'));
    }

    private function saveVendor($entity, bool $isNew): bool
    {
        $data = $this->request->getData();
        if (trim((string)($data['name'] ?? '')) === '') {
            $this->Flash->error('Vendor name is required.');

            return false;
        }
        $entity = $this->fetchTable('ItVendors')->patchEntity($entity, $data + [
            'status' => empty($data['status']) ? 0 : 1,
            'modified' => $this->now(),
            'created' => $isNew ? $this->now() : $entity->created,
        ]);
        if ($this->fetchTable('ItVendors')->save($entity)) {
            $this->auditLog($isNew ? 'it_vendor_create' : 'it_vendor_update', 'it_vendor', $entity->name, (int)$entity->id);
            $this->Flash->success('Vendor saved.');

            return true;
        }
        $this->Flash->error('Could not save the vendor.');

        return false;
    }
}
