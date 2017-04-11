<?php

namespace App\Bundle\CoreBundle\Twig;

use Liip\ImagineBundle\Controller\ImagineController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\Request;

class GetImageExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $baseWebDir;

    /**
     * @param string $baseWebDir
     */
    public function __construct($baseWebDir)
    {
        $this->baseWebDir = $baseWebDir;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            'getImage' => new \Twig_Function_Method($this, 'getImage'),
        ];
    }

    /**
     * @param string $imageName
     * @param string $categoryImage
     * @return string
     */
    public function getImage($imageName, $categoryImage)
    {
        $path = $this->baseWebDir . '/images/' . $categoryImage . '/' . $imageName;

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_core_bundle.twig_extension.get_image_extension';
    }
}