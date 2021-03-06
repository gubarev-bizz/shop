<?php

namespace App\Bundle\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['count'] === null) {
            $builder
                ->add('count', NumberType::class, [
                    'label' => 'Count',
                    'data' => 1
                ])
            ;
        } else {
            $builder
                ->add('count', HiddenType::class, [
                    'label' => 'Count',
                    'data' => 1
                ])
            ;
        }

        $builder
            ->add('productId', HiddenType::class, [
                'data' => $options['productId']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'action' => null,
            'productId' => null,
            'count' => null,
        ]);
    }
}
