<?php
namespace SynerBay\Functions;

use SynerBay\Traits\WPAction;
use SynerBay\Module\Product as ProductModule;

class Product
{
    use WPAction;

    private ProductModule $productModule;

    public function __construct()
    {
        $this->productModule = new ProductModule();

        $this->addAction('get_product', 'getProduct');
        $this->addAction('get_products_for_current_user', 'getProductsForCurrentUser');
    }

    public function getProduct(int $productID)
    {
        return $this->productModule->getProductData($productID);
    }

    public function getProductsForCurrentUser()
    {
        $searchParams = [
            'author_id' => get_current_user_id()
        ];

        return $this->productModule->search($searchParams);
    }
}