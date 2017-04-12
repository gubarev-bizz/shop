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
    }
}
