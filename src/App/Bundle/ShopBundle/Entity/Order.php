<?php

namespace App\Bundle\ShopBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use App\Bundle\ShopBundle\Entity\DTO\ProductCountDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

/**
 * @ORM\Entity()
 * @ORM\Table(name="order_checkout")
 */
class Order
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;

    const STATUS_INITIAL = 'INITIAL';
    const STATUS_NEW = 'NEW';
    const TAKEN_PROCESSING = 'TAKEN_PROCESSING';
    const AWAITING_PAYMENT = 'AWAITING_PAYMENT';
    const AWAITING_SHIPMENT = 'AWAITING_SHIPMENT';
    const EXECUTED = 'EXECUTED';
    const CANCELED = 'CANCELED';

    const DELIVERY_INTIME = 'INTIME';
    const DELIVERY_NOVA_POSHTA = 'NOVA_POSHTA';
    const DELIVERY_GUNSEL = 'GUNSEL';
    const DELIVERY_DELIVERY = 'DELIVERY';
    const DELIVERY_AUTOLUX = 'AUTOLUX';
    const DELIVERY_NIGHT_EXPRESS = 'NIGHT_EXPRESS';

    const PAYMENT_TYPE_CASHLESS_PAYMENTS = 'CASHLESS_PAYMENTS';
    const PAYMENT_TYPE_CASH_DELIVERY = 'CASH_DELIVERY';

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=false)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name",type="string", nullable=false)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=false)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=false)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Email()
     */
    private $email;

    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default" = 0})
     * @SymfonyConstraints\Type(type="numeric")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery", type="string", nullable=false)
     * @SymfonyConstraints\NotBlank()
     */
    private $delivery;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", nullable=false)
     * @SymfonyConstraints\NotBlank()
     */
    private $paymentType;

    /**
     * @var ArrayCollection|ProductItem[]
     *
     * @ORM\OneToMany(targetEntity="ProductItem", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     * @SymfonyConstraints\NotBlank()
     */
    private $productItems;

    /**
     * @var ProductCountDTO[]
     *
     */
    protected $products;

    public function __construct()
    {
        $this->productItems = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param mixed $delivery
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return ProductItem[]|ArrayCollection
     */
    public function getProductItems()
    {
        return $this->productItems;
    }

    /**
     * @param ProductItem[]|ArrayCollection $productItems
     *
     * @return $this
     */
    public function setProductItems($productItems)
    {
        $this->productItems = $productItems;

        return $this;
    }

    /**
     * @param ProductItem $item
     *
     * @return $this
     */
    public function addProductItem($item)
    {
        $this->productItems[] = $item;

        return $this;
    }

    /**
     * @param ProductItem $item
     * @return $this
     */
    public function removeProductItem($item)
    {
        $this->productItems->removeElement($item);

        return $this;
    }

    /**
     * @return ProductCountDTO[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ProductCountDTO[]|ArrayCollection $products
     *
     * @return $this
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @param ProductCountDTO $product
     */
    public function addProduct(ProductCountDTO $product)
    {
        $this->products[] = $product;
    }

    /**
     * @param ProductCountDTO $product
     *
     * @return $this
     */
    public function removeProduct($product)
    {
        $this->products->removeElement($product);

        return $this;
    }
}
