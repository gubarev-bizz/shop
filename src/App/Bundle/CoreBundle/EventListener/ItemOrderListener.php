<?php

namespace App\Bundle\CoreBundle\EventListener;

use App\Bundle\ShopBundle\Entity\ItemOrder;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ItemOrderListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ItemOrder) {
            return;
        }

        $entity->setOriginalAmount($entity->getAmount());
    }
}
