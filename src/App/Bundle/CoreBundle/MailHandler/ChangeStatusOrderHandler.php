<?php

namespace App\Bundle\CoreBundle\MailHandler;

use Symfony\Component\OptionsResolver\OptionsResolver;
use VisualCraft\Bundle\MailerBundle\MailHandler\TemplatingAwareMailHandlerTrait;
use VisualCraft\Bundle\MailerBundle\MailHandlerInterface;

class ChangeStatusOrderHandler implements MailHandlerInterface
{
    use TemplatingAwareMailHandlerTrait;

    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver
            ->setRequired(['order'])
        ;
    }

    /**
     * @param \Swift_Message $message
     * @param array $options
     */
    public function buildMessage(\Swift_Message $message, array $options)
    {
        $message
            ->setSubject($this->renderSubject('AppCoreBundle:Mail:ChangeStatusOrder-subject.html.twig'))
            ->setTo($this->email)
            ->setFrom($this->email)
            ->setBody($this->renderBody('AppCoreBundle:Mail:ChangeStatusOrder.html.twig', [
                'order' => $options['order']
            ]))
        ;
    }
}