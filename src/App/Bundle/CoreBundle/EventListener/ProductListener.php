<?php

namespace App\Bundle\CoreBundle\EventListener;

use App\Bundle\ShopBundle\Entity\Product;
use App\Bundle\ShopBundle\Entity\ProductItem;
use App\Bundle\ShopBundle\Entity\Order;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ProductListener
{
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Product) {
            return;
        }

        $em = $args->getEntityManager();
        $entity = $em->getRepository('AppShopBundle:Product')->find($entity->getId());
        $items = $em->getRepository('AppShopBundle:ItemOrder')->findByProduct($entity);
        $orderRepository = $em->getRepository('AppShopBundle:Order');

        if ($items) {
            /** @var ProductItem $item */
            foreach ($items as $item) {
                $order = $orderRepository->find($item->getOrder()->getId());

                if ($order instanceof Order && !$order->isLock()) {
                    $item->setOriginalAmount($entity->getPriceUah());
                    $item->setAmount($entity->getPriceUah() * $item->getQuantity());
                    $orderAmount = 0;

                    /** @var ProductItem $itemOrder */
                    foreach ($order->getItems() as $itemOrder) {
                        $orderAmount += $itemOrder->getAmount();
                    }

                    $order->setAmount($orderAmount);
                }
            }

            $em->flush();
        }
    }
}
