<?php

namespace App\Bundle\ShopBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

///**
// * @ORM\Entity(repositoryClass="App\Bundle\ShopBundle\Entity\Repository\ProductItemRepository")
// * @ORM\Table(name="item_order")
// */
class ProductItem
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $originalAmount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @SymfonyConstraints\NotBlank()
     */
    protected $order;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="App\Bundle\CoreBundle\Entity\Product",
     *     inversedBy="items",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(name="products_items")
     */
    protected $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @param Product $products
     */
    public function addProduct(Product $products)
    {
        $this->products[] = $products;
    }

    /**
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
        $order->addItem($this);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getOriginalAmount()
    {
        return $this->originalAmount;
    }

    /**
     * @param float $originalAmount
     */
    public function setOriginalAmount($originalAmount)
    {
        $this->originalAmount = $originalAmount;
    }

}
