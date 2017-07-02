<?php

namespace App\Bundle\ShopBundle\Entity\Traits;


trait ProductEntityTrait
{
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
     * @Gedmo\Slug(fields={"title"}, unique=true)
     * @SymfonyConstraints\Type(type="string")
     * @SymfonyConstraints\Length(max=255)
     */
    private $slug;

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
     * @Serializer\Exclude()
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
     * @return array
     */
    public function jsonSerializer()
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'title' => $this->getTitle(),
            'price' => $this->getRealPrice(),
        ];
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

}
