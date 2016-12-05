<?php

namespace App\Bundle\ShopBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
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

    const STATUS_INITIAL = 'initial';
    const TAKEN_PROCESSING = 'TAKEN_PROCESSING';
    const AWAITING_PAYMENT = 'AWAITING_PAYMENT';
    const AWAITING_SHIPMENT = 'AWAITING_SHIPMENT';
    const EXECUTED = 'EXECUTED';
    const CANCELED = 'CANCELED';

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
     * @ORM\Column(type="float", nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="ItemOrder", mappedBy="order")
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ItemOrder[] $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param ItemOrder $item
     * @return $this
     */
    public function addItem($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param ItemOrder $item
     * @return $this
     */
    public function removeItems($item)
    {
        $this->items->remove($item);
        $item->setOrder(null);

        return $this;
    }
}
