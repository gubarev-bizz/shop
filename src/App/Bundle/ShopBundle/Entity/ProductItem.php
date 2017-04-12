<?php

namespace App\Bundle\ShopBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use App\Bundle\ShopBundle\Entity\Traits\ProductEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Bundle\ShopBundle\Entity\Repository\ProductItemRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class ProductItem
{
    use IdentifiableEntityTrait;
    use ProductEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="productItems")
     * @SymfonyConstraints\NotBlank()
     */
    protected $order;

    public function __construct()
    {

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
        $order->addProductItem($this);

        return $this;
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
}
