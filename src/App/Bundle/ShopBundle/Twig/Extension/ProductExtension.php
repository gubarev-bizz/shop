<?php

namespace App\Bundle\ShopBundle\Twig\Extension;

class ProductExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('currency', array($this, 'currencyFilter')),
        );
    }

    /**
     * @param float $price
     *
     * @return string
     */
    public function currencyFilter($price)
    {
        return $price . ' грн.';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'product';
    }
}
