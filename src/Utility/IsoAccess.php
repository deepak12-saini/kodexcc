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
        'production_supervisor' => 'Production Supervisor',
        'purchase_manager' => 'Purchase / Supplier Manager',
        'maintenance_manager' => 'Maintenance Manager',
        'auditor' => 'Auditor / Read-only',
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
        'maintenance_manager' => ['maintenance'],
        'auditor' => [],
    ];

    /** @var array<string, list<string>> */
    private const VIEW = [
        'super_admin' => ['*'],
        'iso_manager' => ['overview', 'documents', 'training', 'inspections', 'audits', 'ncr', 'corrective'],
        'document_controller' => ['overview', 'documents', 'training'],
        'approver' => ['overview', 'documents', 'corrective', 'reviews'],
        'qa_inspector' => ['overview', 'inspections', 'receipts'],
        'production_supervisor' => ['overview', 'batches', 'inspections'],
        'purchase_manager' => ['overview', 'suppliers', 'materials', 'receipts'],
        'maintenance_manager' => ['overview', 'maintenance'],
        'auditor' => ['*'],
    ];

    public static function canView(string $role, string $section): bool
    {
        return self::allows(self::VIEW, $role, $section);
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
