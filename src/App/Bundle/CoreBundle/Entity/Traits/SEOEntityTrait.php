<?php

namespace App\Bundle\CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SEOEntityTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $seoDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $seoTags;

    /**
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * @param string $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return string
     */
    public function getSeoTags()
    {
        return $this->seoTags;
    }

    /**
     * @param string $seoTags
     */
    public function setSeoTags($seoTags)
    {
        $this->seoTags = $seoTags;
    }
}
