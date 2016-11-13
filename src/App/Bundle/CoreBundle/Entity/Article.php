<?php

namespace App\Bundle\CoreBundle\Entity;

use App\Bundle\CoreBundle\Entity\Traits\IdentifiableEntityTrait;
use App\Bundle\CoreBundle\Entity\Traits\TimestampableEntityTrait;
use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

/**
 * @ORM\Entity()
 */
class Article
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;

    const TYPE_ARTICLE = 'article';
    const TYPE_NEWS = 'news';
    const TYPE_STOCK = 'stock';

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
     * @ORM\Column(type="text")
     * @SymfonyConstraints\NotBlank()
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(max="255")
     */
    private $type;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(
     *     targetEntity="Application\Sonata\MediaBundle\Entity\Media",
     *     cascade={"persist"},
     *     fetch="LAZY"
     * )
     */
    private $image;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return MediaInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param MediaInterface $image
     */
    public function setImage(MediaInterface $image)
    {
        $this->image = $image;
    }
}
