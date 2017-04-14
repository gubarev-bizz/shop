<?php

namespace App\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CallUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true
            ])
            ->add('email', EmailType::class)
            ->add('phone', TextType::class, [
                'required' => true,
            ])
            ->add('message', TextareaType::class)
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-success'
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'app_core_bundle_call_us_type';
    }
}
