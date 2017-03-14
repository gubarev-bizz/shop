<?php

namespace App\Bundle\CoreBundle\Workers;

use App\Bundle\AdminBundle\Parser\ParserProcess;
use App\Bundle\ShopBundle\Service\MultiCurrency;
use Doctrine\ORM\EntityManager;
use VisualCraft\BeanstalkScheduler\Job;

class ParseProductWorker extends AbstractWorker
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ParserProcess
     */
    private $parserProcess;

    /**
     * @var MultiCurrency
     */
    private $multiCurrency;

    /**
     * @param EntityManager $em
     * @param ParserProcess $parserProcess
     * @param MultiCurrency $multiCurrency
     */
    public function __construct(
        EntityManager $em,
        ParserProcess $parserProcess,
        MultiCurrency $multiCurrency
    ) {
        $this->em = $em;
        $this->parserProcess = $parserProcess;
        $this->multiCurrency = $multiCurrency;
    }

    /**
     * @param Job $job
     */
    public function work(Job $job)
    {
        $this->checkDbConnection($this->em);
        $jobParameters = $job->getPayload();
        $this->parserProcess->parser((int) $jobParameters['importId'], $jobParameters['filePath']);
        $this->multiCurrency->refreshCurrency();
    }
}
