<?php

namespace App\Bundle\ShopBundle\Service;

use App\Bundle\ShopBundle\Entity\MultiCurrency as MultiCurrencyEntity;
use Doctrine\ORM\EntityManager;

class MultiCurrency
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
     * @param string $currency
     * @param float $amount
     */
    public function process($currency, $amount)
    {
        switch ($currency) {
            case 'USD':
                $this->setUsdCurrency($amount);
                break;
            case 'EUR':
                $this->setEurCurrency($amount);
        }
    }

    /**
     * @param string $key
     * @return float|int
     */
    public function get($key)
    {
        $value = $this->em->getRepository('AppShopBundle:MultiCurrency')->findOneBy([
            'key' => $key
        ]);

        return ($value) ? $value->getValue() : 0;
    }

    public function refreshCurrency()
    {
        $eurProducts = $this->em->getRepository('AppCoreBundle:Product')->findBy([
            'currency' => 'EUR'
        ]);
        $usdProducts = $this->em->getRepository('AppCoreBundle:Product')->findBy([
            'currency' => 'USD'
        ]);

        foreach ($eurProducts as $eurProduct) {
            $amount = $eurProduct->getRealPrice() * $this->get('EUR');
            $eurProduct->setPriceUah($amount);
        }

        foreach ($usdProducts as $usdProduct) {
            $amount = $usdProduct->getRealPrice() * $this->get('USD');
            $usdProduct->setPriceUah($amount);
        }

        $this->em->flush();
    }

    /**
     * @param float $amount
     */
    private function setEurCurrency($amount)
    {
        $eurCurrency = $this->em->getRepository('AppShopBundle:MultiCurrency')->findOneBy([
            'key' => 'eur'
        ]);

        if (!$eurCurrency) {
            $eurCurrency = new MultiCurrencyEntity();
            $eurCurrency->setKey('eur');
            $eurCurrency->setValue($amount);
            $this->em->persist($eurCurrency);
        } else {
            $eurCurrency->setValue($amount);
        }

        $this->em->flush();
    }

    /**
     * @param float $amount
     */
    private function setUsdCurrency($amount)
    {
        $usdCurrency = $this->em->getRepository('AppShopBundle:MultiCurrency')->findOneBy([
            'key' => 'usd'
        ]);

        if (!$this->get('usd')) {
            $usdCurrency = new MultiCurrencyEntity();
            $usdCurrency->setKey('usd');
            $usdCurrency->setValue($amount);
            $this->em->persist($usdCurrency);
        } else {
            $usdCurrency->setValue($amount);
        }

        $this->em->flush();
    }
}
