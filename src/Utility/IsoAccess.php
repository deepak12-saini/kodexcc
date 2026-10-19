<?php
declare(strict_types=1);

namespace App\Utility;

/**
 * ISO 9001 role checks for the admin panel.
 */
class IsoAccess
{
    public const ROLES = [
        'super_admin' => 'Super Admin',
        'iso_manager' => 'ISO Manager',
        'document_controller' => 'Document Controller',
        'approver' => 'Approver / Management',
        'qa_inspector' => 'QA Inspector',
        'production_supervisor' => 'Production',
        'purchase_manager' => 'Purchase',
        'sales_executive' => 'Sales',
        'accounts_manager' => 'Accounts',
        'maintenance_manager' => 'Maintenance Manager',
        'auditor' => 'Auditor / Read-only',
    ];

    /** Menus an admin can tick on the employee form. */
    public const MENUS = [
        'documents' => 'Documents',
        'training' => 'Training',
        'suppliers' => 'Suppliers',
        'materials' => 'Materials',
        'receipts' => 'Incoming material',
        'batches' => 'Production',
        'inspections' => 'QC inspections',
        'ncr' => 'Non-conformance',
        'corrective' => 'Corrective action',
        'maintenance' => 'Maintenance',
        'audits' => 'Internal audit',
        'complaints' => 'Complaints',
        'reviews' => 'Management review',
    ];

    /** @var array<string, list<string>> */
    private const WRITE = [
        'super_admin' => ['*'],
        'iso_manager' => ['documents', 'training', 'inspections', 'audits', 'ncr', 'corrective'],
        'document_controller' => ['documents', 'training'],
        'approver' => ['documents', 'corrective', 'reviews'],
        'qa_inspector' => ['inspections', 'receipts'],
        'production_supervisor' => ['batches', 'inspections'],
        'purchase_manager' => ['suppliers', 'materials', 'receipts'],
        'sales_executive' => [],
        'accounts_manager' => [],
        'maintenance_manager' => ['maintenance'],
        'auditor' => [],
    ];

    /** @var array<string, list<string>> */
    private const VIEW = [
        'super_admin' => ['*'],
        'iso_manager' => ['overview', 'documents', 'training', 'inspections', 'audits', 'ncr', 'corrective'],
        'document_controller' => ['overview', 'documents', 'training', 'ncr'],
        'approver' => ['overview', 'documents', 'corrective', 'reviews', 'ncr'],
        'qa_inspector' => ['overview', 'inspections', 'receipts', 'ncr'],
        'production_supervisor' => ['overview', 'batches', 'inspections', 'ncr'],
        'purchase_manager' => ['overview', 'suppliers', 'materials', 'receipts', 'ncr'],
        'sales_executive' => ['overview', 'ncr'],
        'accounts_manager' => ['overview', 'ncr'],
        'maintenance_manager' => ['overview', 'maintenance', 'ncr'],
        'auditor' => ['*'],
    ];

    public static function canView(string $role, string $section, ?array $assigned = null): bool
    {
        if ($assigned !== null && $role !== 'super_admin') {
            if ($section === 'overview') {
                return $assigned !== [];
            }

            return in_array($section, $assigned, true);
        }

        return self::allows(self::VIEW, $role, $section);
    }

    /**
     * Menus the role opens when the admin has not ticked a custom list yet.
     *
     * @return list<string>
     */
    public static function menusForRole(string $role): array
    {
        $menus = [];
        foreach (self::MENUS as $key => $label) {
            if (self::allows(self::VIEW, $role, $key)) {
                $menus[] = $key;
            }
        }

        return $menus;
    }

    /**
     * @return list<string>
     */
    public static function cleanMenus(mixed $posted): array
    {
        if (!is_array($posted)) {
            return [];
        }
        $menus = [];
        foreach ($posted as $key) {
            $key = (string)$key;
            if (isset(self::MENUS[$key])) {
                $menus[] = $key;
            }
        }

        return array_values(array_unique($menus));
    }

    public static function canWrite(string $role, string $section): bool
    {
        return self::allows(self::WRITE, $role, $section);
    }

    /**
     * @param array<string, list<string>> $map
     */
    private static function allows(array $map, string $role, string $section): bool
    {
        $list = $map[$role] ?? [];
        if (in_array('*', $list, true)) {
            return true;
        }

        return in_array($section, $list, true);
    }
}
