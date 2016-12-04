<?php

namespace App\Bundle\CoreBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

/**
 * @ORM\Entity()
 */
class Product
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;

    const CURRENCY_USD = 'USD';
    const CURRENCY_UAH = 'UAH';
    const CURRENCY_EUR = 'EUR';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @SymfonyConstraints\NotBlank()
     */
    private $content;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Type(type="numeric")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceUah;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @SymfonyConstraints\NotBlank()
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @SymfonyConstraints\NotBlank()
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @SymfonyConstraints\NotBlank()
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Review", mappedBy="product")
     */
    protected $reviews;

    /**
     * @ORM\ManyToOne(targetEntity="Manufacturer", inversedBy="products")
     */
    protected $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="products")
     */
    protected $country;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="product", cascade={"persist"} , orphanRemoval=true)
     */
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="Slider", mappedBy="product")
     */
    protected $sliders;


    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->sliders = new ArrayCollection();
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        $user->addProduct($this);

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        $category->addProduct($this);

        return $this;
    }

    public function getRealPrice()
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        if ($this->getPriceUah() !== null) {
            return $this->getPriceUah();
        }

        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getPriceUah()
    {
        return $this->priceUah;
    }

    /**
     * @param float $priceUah
     */
    public function setPriceUah($priceUah)
    {
        $this->priceUah = $priceUah;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param Manufacturer $manufacturer
     * @return $this
     */
    public function setManufacturer(Manufacturer $manufacturer)
    {
        $this->manufacturer = $manufacturer;
        $manufacturer->addProduct($this);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return $this
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        $country->addProduct($this);

        return $this;
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
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param Review[] $reviews
     * @return $this
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * @param Review $review
     * @return $this
     */
    public function addReview($review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * @param Review $review
     * @return $this
     */
    public function removeReviews($review)
    {
        $this->reviews->remove($review);
        $review->setProduct(null);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Image[] $images
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function addImage($image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function removeImages($image)
    {
        $this->images->remove($image);
        $image->setProduct(null);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSliders()
    {
        return $this->sliders;
    }

    /**
     * @param Slider[] $sliders
     * @return $this
     */
    public function setSliders($sliders)
    {
        $this->sliders = $sliders;

        return $this;
    }

    /**
     * @param Slider $slider
     * @return $this
     */
    public function addSlider($slider)
    {
        $this->sliders[] = $slider;

        return $this;
    }

    /**
     * @param Slider $slider
     * @return $this
     */
    public function removeSliders($slider)
    {
        $this->sliders->remove($slider);
        $slider->setProduct(null);

        return $this;
    }
}
