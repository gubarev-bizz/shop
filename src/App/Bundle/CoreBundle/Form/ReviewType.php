<?php

namespace App\Bundle\CoreBundle\Form;

use App\Bundle\CoreBundle\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name *',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email *',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content *',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Review::class,
                'csrf_protection' => true,
            ])
        ;
    }

    public function getName()
    {
        return 'app_core_bundle_review_product_type';
    }
}
