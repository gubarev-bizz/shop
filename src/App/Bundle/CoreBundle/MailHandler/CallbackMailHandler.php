<?php

namespace App\Bundle\CoreBundle\MailHandler;

use Symfony\Component\OptionsResolver\OptionsResolver;
use VisualCraft\Bundle\MailerBundle\MailHandlerInterface;

class CallbackMailHandler implements MailHandlerInterface
{

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        // TODO: Implement configureOptions() method.
    }

    /**
     * @param \Swift_Message $message
     * @param array $options
     */
    public function buildMessage(\Swift_Message $message, array $options)
    {
        // TODO: Implement buildMessage() method.
    }
}
