<?php

namespace App\Bundle\ShopBundle\Entity;

use App\Bundle\CoreBundle\Entity\Category;
use App\Bundle\CoreBundle\Entity\Country;
use App\Bundle\CoreBundle\Entity\Image;
use App\Bundle\CoreBundle\Entity\Manufacturer;
use App\Bundle\CoreBundle\Entity\Review;
use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\SEOEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use App\Bundle\CoreBundle\Entity\User;
use App\Bundle\ShopBundle\Entity\Traits\ProductEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use JMS\Serializer\Annotation as Serializer;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Bundle\ShopBundle\Entity\Repository\ProductRepository")
 * @Vich\Uploadable
 * @Serializer\ExclusionPolicy("all")
 */
class Product
{
    use IdentifiableEntityTrait;
    use ProductEntityTrait;
    use TimestampableEntityTrait;
    use SEOEntityTrait;

    const CURRENCY_USD = 'USD';
    const CURRENCY_UAH = 'UAH';
    const CURRENCY_EUR = 'EUR';

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Bundle\CoreBundle\Entity\User", inversedBy="products")
     * @Serializer\Expose
     * @Serializer\Type("ArrayCollection")
     */
    protected $user;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="App\Bundle\CoreBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     * @SymfonyConstraints\NotBlank()
     * @Serializer\Expose
     */
    protected $category;

    /**
     * @var Review[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Bundle\CoreBundle\Entity\Review", mappedBy="product")
     */
    protected $reviews;

    /**
     * @var Manufacturer
     *
     * @ORM\ManyToOne(targetEntity="App\Bundle\CoreBundle\Entity\Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Bundle\CoreBundle\Entity\Country", inversedBy="products")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $country;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Bundle\CoreBundle\Entity\Image",
     *     mappedBy="product",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    protected $images;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

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
    private $weightTowbar;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Length(max="250")
     */
    private $numberContacts;

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

    /**
     * @var ArrayCollection|ProductItem[]
     *
     * @ORM\OneToMany(
     *     targetEntity="ProductItem",
     *     mappedBy="product",
     *     cascade={"persist"}
     * )
     */
    private $productItems;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @SymfonyConstraints\NotNull()
     */
    private $active;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->productItems = new ArrayCollection();
        $this->top = false;
        $this->active = true;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Product
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
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
    public function addImage(Image $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function removeImage(Image $image)
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            $image->setProduct(null);
        }

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

    /**
     * @return string
     */
    public function getWeightTowbar()
    {
        return $this->weightTowbar;
    }

    /**
     * @param string $weightTowbar
     */
    public function setWeightTowbar($weightTowbar)
    {
        $this->weightTowbar = $weightTowbar;
    }

    /**
     * @return string
     */
    public function getNumberContacts()
    {
        return $this->numberContacts;
    }

    /**
     * @param string $numberContacts
     */
    public function setNumberContacts($numberContacts)
    {
        $this->numberContacts = $numberContacts;
    }

    /**
     * @return ProductItem[]|ArrayCollection
     */
    public function getProductItems()
    {
        return $this->productItems;
    }

    /**
     * @param ProductItem $productItems
     */
    public function setProductItems(ProductItem $productItems)
    {
        $this->productItems = $productItems;
    }

    /**
     * @param ProductItem $productItem
     *
     * @return $this
     */
    public function addProductItem(ProductItem $productItem)
    {
        $this->productItems[] = $productItem;

        return $this;
    }

    /**
     * @param $productItem
     *
     * @return $this
     */
    public function removeProductItems(ProductItem $productItem)
    {
        $this->productItems->remove($productItem);
        $productItem->setProduct(null);

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}
