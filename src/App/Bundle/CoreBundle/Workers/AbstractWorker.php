<?php

namespace App\Bundle\CoreBundle\Workers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use VisualCraft\BeanstalkScheduler\Job;
use VisualCraft\BeanstalkScheduler\WorkerInterface;

abstract class AbstractWorker implements WorkerInterface
{
    /**
     * @param EntityManagerInterface $em
     * @throws DBALException
     */
    protected function checkDbConnection(EntityManagerInterface $em)
    {
        /** @var Connection $connection */
        $connection = $em->getConnection();
        $reopened = false;

        try {
            $connection->connect();
            $connection->query($connection->getDatabasePlatform()->getDummySelectSQL());
        } catch (DBALException $e) {
            $connection->close();
            $connection->connect();
            $reopened = true;
        }

        if ($reopened) {
            $connection->query($connection->getDatabasePlatform()->getDummySelectSQL());
        }

        /** @var UnitOfWork $uof */
        $uof = $em->getUnitOfWork();
        $uof->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function isReschedulableException(\Exception $e)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function fail(Job $job)
    {

    }
}
