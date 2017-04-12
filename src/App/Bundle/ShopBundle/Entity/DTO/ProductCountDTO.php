<?php

namespace App\Bundle\ShopBundle\Entity\DTO;

use App\Bundle\ShopBundle\Entity\Product;

class ProductCountDTO
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @var Product
     */
    private $product;

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }
}
