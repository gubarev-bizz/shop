<?php

namespace App\Bundle\CoreBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use App\Bundle\ShopBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

/**
 * @ORM\Entity()
 */
class Country
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Bundle\ShopBundle\Entity\Product", mappedBy="country")
     */
    protected $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     * @return $this
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addProduct($product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function removeProducts($product)
    {
        $this->products->remove($product);
        $product->setCountry(null);

        return $this;
    }
}
