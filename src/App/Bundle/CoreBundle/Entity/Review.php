<?php

namespace App\Bundle\CoreBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

/**
 * @ORM\Entity()
 */
class Review
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Email()
     * @SymfonyConstraints\Length(max="255")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     * @SymfonyConstraints\NotBlank()
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $approve;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="reviews")
     * @SymfonyConstraints\NotBlank()
     */
    protected $product;

    public function __construct()
    {
        $this->approve = false;
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
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        $product->addReview($this);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return boolean
     */
    public function isApprove()
    {
        return $this->approve;
    }

    /**
     * @param boolean $approve
     */
    public function setApprove($approve)
    {
        $this->approve = $approve;
    }
}
