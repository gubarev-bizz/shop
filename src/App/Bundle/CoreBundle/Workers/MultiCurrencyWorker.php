<?php

namespace App\Bundle\CoreBundle\Workers;

use App\Bundle\ShopBundle\Service\MultiCurrency;
use Doctrine\ORM\EntityManager;
use VisualCraft\BeanstalkScheduler\Job;

class MultiCurrencyWorker extends AbstractWorker
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var MultiCurrency
     */
    private $multiCurrency;

    /**
     * @param EntityManager $em
     * @param MultiCurrency $multiCurrency
     */
    public function __construct(
        EntityManager $em,
        MultiCurrency $multiCurrency
    ) {
        $this->em = $em;
        $this->multiCurrency = $multiCurrency;
    }

    /**
     * @param Job $job
     */
    public function work(Job $job)
    {
        $this->checkDbConnection($this->em);
        $this->multiCurrency->refreshCurrency();
    }
}
