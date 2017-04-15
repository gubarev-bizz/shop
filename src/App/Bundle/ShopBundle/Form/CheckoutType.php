<?php

namespace App\Bundle\ShopBundle\Form;

use App\Bundle\ShopBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'Patronymic',
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => true,
            ])
            ->add('delivery', ChoiceType::class, [
                'label' => 'Select type of delivery',
                'required' => true,
                'choices' => [
                    'AutoluxLabel' => Order::DELIVERY_AUTOLUX,
                    'DeliveryLabel' => Order::DELIVERY_DELIVERY,
                    'NovaPoshtaLabel' => Order::DELIVERY_NOVA_POSHTA,
                    'NightExpressLabel' => Order::DELIVERY_NIGHT_EXPRESS,
                    'GunselLabel' => Order::DELIVERY_GUNSEL,
                    'IntimeLabel' => Order::DELIVERY_INTIME,
                ],
            ])
            ->add('paymentType', ChoiceType::class, [
                'label' => 'Select payment type',
                'choices' => [
                    'CashDeliveryLabel' => Order::PAYMENT_TYPE_CASH_DELIVERY,
                    'CashlessPaymentsLabel' => Order::PAYMENT_TYPE_CASHLESS_PAYMENTS,
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
