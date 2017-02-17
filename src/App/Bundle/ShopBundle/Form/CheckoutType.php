<?php

namespace App\Bundle\ShopBundle\Form;

use App\Bundle\ShopBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Имя',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Фамилия',
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('delivery', ChoiceType::class, [
                'label' => 'Выберите тип доставки',
                'required' => true,
                'choices' => [
                    'Автолюкс' => Order::DELIVERY_AUTOLUX,
                    'Деливери' => Order::DELIVERY_DELIVERY,
                    'Новая Почта' => Order::DELIVERY_NOVA_POSHTA,
                    'Ночной экспресс' => Order::DELIVERY_NIGHT_EXPRESS,
                    'Гюнсел' => Order::DELIVERY_GUNSEL,
                    'Интайм' => Order::DELIVERY_INTIME,
                ],
            ])
            ->add('paymentType', ChoiceType::class, [
                'label' => 'Выберите тип оплаты',
                'choices' => [
                    'Наложенный платеж' => Order::PAYMENT_TYPE_CASH_DELIVERY,
                    'Безналичный расчет' => Order::PAYMENT_TYPE_CASHLESS_PAYMENTS,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Bundle\ShopBundle\Entity\Order',
        ]);
    }
}
