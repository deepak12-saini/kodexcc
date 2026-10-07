<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class DashboardController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'Admin & IT Dashboard');
        $assets = $this->fetchTable('ItAssets');
        $tickets = $this->fetchTable('ItTickets');
        $purchases = $this->fetchTable('ItPurchaseRequests');
        $repairs = $this->fetchTable('ItRepairs');

        $totalEmployees = $this->fetchTable('HrEmployees')->find()->where(['status' => 'active'])->count();
        $totalAssets = $assets->find()->count();
        $assignedAssets = $assets->find()->where(['status' => 'assigned'])->count();
        $availableAssets = $assets->find()->where(['status' => 'available'])->count();
        $faultyAssets = $assets->find()->where(['status' => 'faulty'])->count();
        $underRepair = $assets->find()->where(['status' => 'under_repair'])->count();
        $openTickets = $tickets->find()->where(['status IN' => ['open', 'assigned', 'in_progress', 'waiting']])->count();
        $pendingPurchases = $purchases->find()->where(['approval_status IN' => ['requested', 'quotation', 'approved', 'purchased']])->count();
        $costQuery = $repairs->find();
        $costRow = $costQuery
            ->select(['total' => $costQuery->func()->sum('actual_cost')])
            ->where(['status IN' => ['completed', 'returned']])
            ->enableHydration(false)
            ->first();
        $repairCost = (float)($costRow['total'] ?? 0);

        $recentTickets = $tickets->find()->contain(['HrEmployees', 'ItAssets'])->orderBy(['ItTickets.id' => 'DESC'])->limit(8)->all();
        $recentRepairs = $repairs->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItRepairs.id' => 'DESC'])->limit(8)->all();
        $recentPurchases = $purchases->find()->contain(['Requesters'])->orderBy(['ItPurchaseRequests.id' => 'DESC'])->limit(8)->all();
        $recentMaintenance = $this->fetchTable('ItMaintenance')->find()->contain(['ItAssets'])->orderBy(['ItMaintenance.id' => 'DESC'])->limit(8)->all();

        $this->set(compact(
            'totalEmployees',
            'totalAssets',
            'assignedAssets',
            'availableAssets',
            'faultyAssets',
            'underRepair',
            'openTickets',
            'pendingPurchases',
            'repairCost',
            'recentTickets',
            'recentRepairs',
            'recentPurchases',
            'recentMaintenance'
        ));
    }
}
