<?php

namespace App\Bundle\AdminBundle\Form;

use App\Bundle\ShopBundle\Service\MultiCurrency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class MultiCurrencyType extends AbstractType
{
    private $multiCurrency;

    /**
     * @param MultiCurrency $multiCurrency
     */
    public function __construct(MultiCurrency $multiCurrency)
    {
        $this->multiCurrency = $multiCurrency;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usd', NumberType::class, [
                'data' => $this->multiCurrency->get('usd'),
            ])
            ->add('eur', NumberType::class, [
                'data' => $this->multiCurrency->get('eur'),
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }
}
