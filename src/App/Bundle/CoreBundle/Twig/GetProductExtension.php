<?php

namespace App\Bundle\CoreBundle\Twig;


use Doctrine\ORM\EntityManager;

class GetProductExtension extends \Twig_Extension
{
    private $em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            'getProduct' => new \Twig_Function_Method($this, 'getProduct'),
        ];
    }

    /**
     * @param int $productId
     * @return string
     */
    public function getProduct($productId)
    {
        $product = $this->em->getRepository('AppCoreBundle:Product')->find($productId);

        if (!$product) {
            return null;
        }

        return $product;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_core_bundle.twig_extension.get_product_extension';
    }
}
