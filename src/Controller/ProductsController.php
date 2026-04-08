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

    public function index(?string $slug = null, ?string $productSlug = null, ?string $tag = null)
    {
        $this->viewBuilder()->setLayout('customkggc_layout');

        if (!empty($tag)) {
            return $this->redirect('/products');
        }

        if ($slug === 'detail' && !empty($productSlug)) {
            $product = $this->fetchTable('Product')->find()
                ->where(['Product.status' => 1, 'Product.slug' => $productSlug])
                ->contain(['Category'])
                ->enableHydration(false)
                ->first();
            if ($product && !empty($product['category']['slug'])) {
                return $this->redirect('/products/' . $product['category']['slug'] . '/' . $productSlug);
            }
        }

        if (!empty($slug) && !empty($productSlug)) {
            $category = $this->fetchTable('Category')->find()
                ->where(['Category.status' => 1, 'Category.slug' => $slug])
                ->enableHydration(false)
                ->first();
            if (!$category) {
                return $this->redirect('/products');
            }

            $product = $this->fetchTable('Product')->find()
                ->where(['Product.status' => 1, 'Product.slug' => $productSlug, 'Product.category_id' => $category['id']])
                ->enableHydration(false)
                ->first();
            if (!$product) {
                return $this->redirect('/products/' . $slug);
            }

            $this->set('products', ['Product' => $product, 'Category' => $category]);
            $this->set('meta_title', $product['meta_title'] ?? ($category['meta_title'] ?? null));
            $this->set('meta_description', $product['meta_description'] ?? ($category['meta_description'] ?? null));
            $this->set('meta_keyword', $product['keyword'] ?? ($category['meta_keyword'] ?? null));
            return $this->render('detail');
        }

        if (!empty($slug)) {
            $category = $this->fetchTable('Category')->find()
                ->where(['Category.status' => 1, 'Category.slug' => $slug])
                ->enableHydration(false)
                ->first();
            if (!$category) {
                return $this->redirect('/products');
            }

            $products = $this->fetchTable('Product')->find()
                ->where(['Product.status' => 1, 'Product.category_id' => $category['id']])
                ->orderBy(['Product.image' => 'DESC'])
                ->enableHydration(false)
                ->toArray();

            $legacyProducts = array_map(fn($row) => ['Product' => $row], $products);
            $this->set('products', $legacyProducts);
            $this->set('slug', $slug);
            $this->set('category_name', $category['category_name'] ?? ucfirst($slug));
            $this->set('meta_title', $category['meta_title'] ?? null);
            $this->set('meta_description', $category['meta_description'] ?? null);
            $this->set('meta_keyword', $category['meta_keyword'] ?? null);
            return $this->render('index');
        }

        $categories = $this->fetchTable('Category')->find()
            ->where(['Category.status' => 1])
            ->orderBy(['Category.category_name' => 'ASC'])
            ->enableHydration(false)
            ->toArray();

        $legacyCategories = array_map(fn($row) => ['Category' => $row], $categories);
        $this->set('category', $legacyCategories);
        $this->set('meta_title', 'Products - Construction Sealants silicones,Primers & Waterproof Membranes Adhesives In Sydney');
        $this->set('meta_description', 'Kodex Australia supplying Construction sealants silicones, waterproof membranes, primers, and adhesives.');
        $this->set('meta_keyword', 'waterproofing products, decorative coating products, wall putty products, primers, sealants');
        return $this->render('category');
    }
}
