<?php

namespace App\Bundle\ShopBundle\Entity\DTO;

use App\Bundle\ShopBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

class ProductCountDTO
{
    /**
     * @var int
     *
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\NotNull()
     * @SymfonyConstraints\Type(type="numeric")
     */
    private $quantity;

    /**
     * @var Product
     *
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\NotNull()
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
