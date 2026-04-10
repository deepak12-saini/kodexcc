<?php
declare(strict_types=1);

namespace App\Controller\Concerns;

use Cake\Datasource\EntityInterface;

/**
 * Maps Purchase entities (with contain) to legacy CakePHP 2 nested array shape for templates.
 */
trait PurchaseLegacyTrait
{
    /**
     * @return array<string, mixed>
     */
    protected function purchaseEntityToLegacyArray(EntityInterface $entity): array
    {
        $full = $entity->toArray();
        $cols = array_keys($this->fetchTable('Purchase')->getSchema()->getColumns());
        $purchase = [];
        foreach ($cols as $col) {
            if (array_key_exists($col, $full)) {
                $purchase[$col] = $full[$col];
            }
        }
        $out = ['Purchase' => $purchase];

        $nappMap = [
            'napp_user' => 'NappUser',
            'napp_user_1' => 'NappUser_1',
            'napp_user1' => 'NappUser_1',
            'nappuser1' => 'NappUser_1',
            'napp_user_2' => 'NappUser_2',
            'napp_user2' => 'NappUser_2',
            'nappuser2' => 'NappUser_2',
        ];
        foreach ($nappMap as $from => $to) {
            if (!empty($full[$from]) && is_array($full[$from])) {
                $out[$to] = $full[$from];
            }
        }

        $reqKey = 'purchase_requirements';
        if (!empty($full[$reqKey]) && is_array($full[$reqKey])) {
            $out['PurchaseRequirement'] = [];
            foreach ($full[$reqKey] as $req) {
                if ($req instanceof EntityInterface) {
                    $out['PurchaseRequirement'][] = $req->toArray();
                } elseif (is_array($req)) {
                    $out['PurchaseRequirement'][] = $req;
                }
            }
        }

        return $out;
    }

    /**
     * @param iterable<\Cake\Datasource\EntityInterface> $page
     * @return list<array<string, mixed>>
     */
    protected function mapPurchasePageToLegacy(iterable $page): array
    {
        $legacy = [];
        foreach ($page as $entity) {
            if ($entity instanceof EntityInterface) {
                $legacy[] = $this->purchaseEntityToLegacyArray($entity);
            }
        }

        return $legacy;
    }

    /**
     * @return array<string, mixed>
     */
    protected function purchaseFindFirstLegacy(?string $id): array
    {
        $contain = ['NappUser', 'NappUser1', 'NappUser2', 'PurchaseRequirements'];
        $entity = $this->fetchTable('Purchase')->find()
            ->contain($contain)
            ->where(['Purchase.id' => $id])
            ->first();

        return $entity instanceof EntityInterface ? $this->purchaseEntityToLegacyArray($entity) : [];
    }
}
