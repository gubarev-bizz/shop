<?php

namespace App\Bundle\CoreBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use App\Bundle\ShopBundle\Entity\ItemOrder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Bundle\CoreBundle\Entity\Repository\ProductRepository")
 * @Serializer\ExclusionPolicy("all")
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
     * @Serializer\Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     * @Serializer\Expose
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @SymfonyConstraints\NotBlank()
     * @Serializer\Expose
     */
    private $content;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Type(type="numeric")
     * @Serializer\Expose
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Expose
     */
    private $priceUah;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @SymfonyConstraints\NotBlank()
     * @Serializer\Expose
     */
    private $currency;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @SymfonyConstraints\NotBlank()
     * @Serializer\Expose
     * @Serializer\Type("ArrayCollection")
     */
    protected $user;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @SymfonyConstraints\NotBlank()
     * @Serializer\Expose
     */
    protected $category;

    /**
     * @var Review[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Review", mappedBy="product")
     */
    protected $reviews;

    /**
     * @var Manufacturer
     *
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

    /**
     * @ORM\ManyToMany(
     *     targetEntity="App\Bundle\ShopBundle\Entity\ItemOrder",
     *     mappedBy="products",
     *     cascade={"persist"}
     * )
     */
    protected $items;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $top;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $ballType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $verticalBurdenBall;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $pullingBurdenBall;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $installationCoordinationModule;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $systemVoltage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $permissibleCurrentValues;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $tractionLoad;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $removingBumper;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $bumperCropping;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $needHarmonizeModule;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $powerSocket;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $verticalLoad;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->sliders = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->top = false;
    }

    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @param ItemOrder $item
     * @return $this
     */
    public function addItem(ItemOrder $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ItemOrder $items
     */
    public function setItems($items)
    {
        $items->addProduct($this);
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

    /**
     * @return boolean
     */
    public function isTop()
    {
        return $this->top;
    }

    /**
     * @param boolean $top
     */
    public function setTop($top)
    {
        $this->top = $top;
    }

    /**
     * @return string
     */
    public function getBallType()
    {
        return $this->ballType;
    }

    /**
     * @param string $ballType
     */
    public function setBallType($ballType)
    {
        $this->ballType = $ballType;
    }

    /**
     * @return string
     */
    public function getVerticalBurdenBall()
    {
        return $this->verticalBurdenBall;
    }

    /**
     * @param string $verticalBurdenBall
     */
    public function setVerticalBurdenBall($verticalBurdenBall)
    {
        $this->verticalBurdenBall = $verticalBurdenBall;
    }

    /**
     * @return string
     */
    public function getPullingBurdenBall()
    {
        return $this->pullingBurdenBall;
    }

    /**
     * @param string $pullingBurdenBall
     */
    public function setPullingBurdenBall($pullingBurdenBall)
    {
        $this->pullingBurdenBall = $pullingBurdenBall;
    }

    /**
     * @return string
     */
    public function getInstallationCoordinationModule()
    {
        return $this->installationCoordinationModule;
    }

    /**
     * @param string $installationCoordinationModule
     */
    public function setInstallationCoordinationModule($installationCoordinationModule)
    {
        $this->installationCoordinationModule = $installationCoordinationModule;
    }

    /**
     * @return string
     */
    public function getSystemVoltage()
    {
        return $this->systemVoltage;
    }

    /**
     * @param string $systemVoltage
     */
    public function setSystemVoltage($systemVoltage)
    {
        $this->systemVoltage = $systemVoltage;
    }

    /**
     * @return string
     */
    public function getPermissibleCurrentValues()
    {
        return $this->permissibleCurrentValues;
    }

    /**
     * @param string $permissibleCurrentValues
     */
    public function setPermissibleCurrentValues($permissibleCurrentValues)
    {
        $this->permissibleCurrentValues = $permissibleCurrentValues;
    }

    /**
     * @return string
     */
    public function getTractionLoad()
    {
        return $this->tractionLoad;
    }

    /**
     * @param string $tractionLoad
     */
    public function setTractionLoad($tractionLoad)
    {
        $this->tractionLoad = $tractionLoad;
    }

    /**
     * @return string
     */
    public function getRemovingBumper()
    {
        return $this->removingBumper;
    }

    /**
     * @param string $removingBumper
     */
    public function setRemovingBumper($removingBumper)
    {
        $this->removingBumper = $removingBumper;
    }

    /**
     * @return string
     */
    public function getBumperCropping()
    {
        return $this->bumperCropping;
    }

    /**
     * @param string $bumperCropping
     */
    public function setBumperCropping($bumperCropping)
    {
        $this->bumperCropping = $bumperCropping;
    }

    /**
     * @return string
     */
    public function getNeedHarmonizeModule()
    {
        return $this->needHarmonizeModule;
    }

    /**
     * @param string $needHarmonizeModule
     */
    public function setNeedHarmonizeModule($needHarmonizeModule)
    {
        $this->needHarmonizeModule = $needHarmonizeModule;
    }

    /**
     * @return string
     */
    public function getPowerSocket()
    {
        return $this->powerSocket;
    }

    /**
     * @param string $powerSocket
     */
    public function setPowerSocket($powerSocket)
    {
        $this->powerSocket = $powerSocket;
    }

    /**
     * @return string
     */
    public function getVerticalLoad()
    {
        return $this->verticalLoad;
    }

    /**
     * @param string $verticalLoad
     */
    public function setVerticalLoad($verticalLoad)
    {
        $this->verticalLoad = $verticalLoad;
    }

    public function jsonSerializer()
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'title' => $this->getTitle(),
        ];
    }
}
