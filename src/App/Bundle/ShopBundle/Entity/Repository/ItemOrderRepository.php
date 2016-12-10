<?php

namespace App\Bundle\ShopBundle\Entity\Repository;

use App\Bundle\CoreBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ItemOrderRepository extends EntityRepository
{
    public function findByProduct(Product $product)
    {
        return $this->createQueryBuilder('io')
            ->leftJoin('io.order', 'o')
            ->leftJoin('io.products', 'p')
            ->andWhere('p.id = :product')
            ->andWhere('io.order IS NOT NULL')
            ->setParameter('product', $product->getId())
            ->getQuery()
            ->getResult()
            ;
    }
}
