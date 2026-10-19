<?php
declare(strict_types=1);

namespace App\Utility;

/**
 * ERP screen checks. Uses the same role stored in the session as ISO (`iso_role`).
 */
class ErpAccess
{
    /** @var array<string, list<string>> */
    private const WRITE = [
        'super_admin' => ['*'],
        'purchase_manager' => ['partners', 'purchase', 'inventory'],
        'qa_inspector' => [],
        'production_supervisor' => ['production'],
        'sales_executive' => ['partners', 'sales'],
        'accounts_manager' => ['banking', 'financials'],
        'approver' => ['purchase_approve', 'sales_approve'],
        'iso_manager' => [],
        'auditor' => [],
    ];

    /** @var array<string, list<string>> */
    private const VIEW = [
        'super_admin' => ['*'],
        'purchase_manager' => ['overview', 'partners', 'purchase', 'inventory'],
        'qa_inspector' => ['overview', 'inventory'],
        'production_supervisor' => ['overview', 'inventory', 'production'],
        'sales_executive' => ['overview', 'partners', 'sales', 'inventory'],
        'accounts_manager' => ['overview', 'purchase', 'sales', 'banking', 'financials'],
        'approver' => ['overview', 'partners', 'purchase', 'inventory', 'production', 'sales', 'banking', 'financials'],
        'iso_manager' => ['overview', 'partners', 'purchase', 'inventory', 'production', 'sales', 'banking', 'financials'],
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
