<?php

namespace App\Bundle\CoreBundle\Twig;


class GetImageExtension extends \Twig_Extension
{
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

        return '/images/' . $categoryImage . '/' . $imageName;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_core_bundle.twig_extension.get_image_extension';
    }
}