<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class ProductsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->callConstants();
        $this->getCategories();
        $this->getCartList();
    }

    /**
     * Public product catalogue retired for private-label OEM positioning.
     * Admin product management remains under Admin/ProductsController.
     */
    public function index(?string $slug = null, ?string $productSlug = null, ?string $tag = null)
    {
        return $this->redirect('/contact-us');
    }
}
