<?php

namespace App\Bundle\CoreBundle\Entity\Repository;

use App\Bundle\CoreBundle\Entity\Category;
use App\Bundle\CoreBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * @param string $title
     * @return Product[]
     */
    public function searchByTitle($title)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where($qb->expr()->like('p.title', ':title'))
            ->setParameter('title', '%' . $title .'%')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Product $product
     * @param Category $category
     * @return array|null
     */
    public function findSimilarProductsByCategory(Product $product, Category $category = null)
    {
        if ($category !== null) {
            return $this->createQueryBuilder('p')
                ->leftJoin('p.category', 'c')
                ->andWhere('c.id = :category')
                ->andWhere('p.id != :product')
                ->setParameters([
                    'category' => $category->getId(),
                    'product' => $product->getId(),
                ])
                ->setMaxResults(3)
                ->getQuery()
                ->getResult()
                ;
        }

        return null;
    }
}
