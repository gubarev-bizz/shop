<?php

namespace App\Bundle\ShopBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $requestStack;

    private $em;

    /**
     * @param RequestStack $requestStack
     * @param EntityManager $entityManager
     */
    public function __construct(RequestStack $requestStack, EntityManager $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->em = $entityManager;
    }

    /**
     * @return array
     */
    public function getCart()
    {
        $cartElements = $this->getCartElements();
        $cart = [];
        $cart['elements'] = [];
        $cart['total'] = 0;

        if ($cartElements !== null) {
            foreach ($cartElements as $key => $cartElement) {
                $product = $this->em->getRepository('AppCoreBundle:Product')->find($key);

                if ($product) {
                    $cart['total'] += $cartElement['price'];
                    $cart['elements'][] = [
                        'product' => $product,
                        'count' => $cartElement['count'],
                        'amount' => $cartElement['price'],
                    ];
                }
            }
        }

        return $cart;
    }

    /**
     * @return array
     */
    public function getCartElements()
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $elements = $session->get('cartElements');

        return $elements;
    }

    public function getAmountCheckout()
    {
        $elements = $this->getCartElements();
        $total = 0;

        foreach ($elements as $key => $element) {
            $product = $this->em->getRepository('AppCoreBundle:Product')->find($key);

            if ($product) {
                $total += $element['count'] * $product->getPrice();
            }
        }

        return $total;
    }

    /**
     * @return bool
     */
    public function cartIsNull()
    {
        if (empty($this->getCart()['elements'])) {
            return true;
        }

        return false;
    }

    public function setCartEmpty()
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $cart = [];
        $cart['elements'] = [];
        $cart['total'] = 0;
        $session->set('cartElements', null);

        return true;
    }
}
