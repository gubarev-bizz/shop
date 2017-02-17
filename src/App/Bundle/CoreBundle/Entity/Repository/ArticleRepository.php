<?php

namespace App\Bundle\CoreBundle\Entity\Repository;

use App\Bundle\CoreBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * @param string $title
     * @return Article[]
     */
    public function searchByTitle($title)
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->where($qb->expr()->like('a.title', ':title'))
            ->setParameter('title', '%' . $title .'%')
            ->getQuery()
            ->getResult()
            ;
    }
}
