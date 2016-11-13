<?php

namespace App\Bundle\ShopBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
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
     * @var string
     *
     * @ORM\Column(type="json_array", nullable=false)
     */
    private $products;

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
     * @return string
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param string $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
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
}
