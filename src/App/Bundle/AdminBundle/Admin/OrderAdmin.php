<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\AdminBundle\Form\Sonata\ProductOrderFieldType;
use App\Bundle\ShopBundle\Entity\Order;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class OrderAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'orders';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text', ['label' => 'Order Id #'])
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('email')
            ->add('amount')
            ->add('status')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'show' => [],
                    'delete' => [],
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basic information')
                ->add('firstName', 'text', ['label' => 'First name'])
                ->add('lastName', 'text', ['label' => 'Last name'])
                ->add('phone', 'text', ['label' => 'Phone'])
                ->add('email', 'text', ['label' => 'Email'])
                ->add('status', 'choice', [
                    'label' => 'Status',
                    'choices' => [
                        'AWAITING PAYMENT' => Order::AWAITING_PAYMENT,
                        'AWAITING SHIPMENT' => Order::AWAITING_SHIPMENT,
                        'TAKEN PROCESSING' => Order::TAKEN_PROCESSING,
                        'CANCELED' => Order::CANCELED,
                        'EXECUTED' => Order::EXECUTED,
                    ],
                ])
            ->end()
        ;
    }



    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->with('Basic information')
                ->add('firstName')
                ->add('lastName')
                ->add('phone')
                ->add('email')
                ->add('status')
                ->add('products', null, [
                    'template' => 'AppAdminBundle:Admin/Field:productOrder.html.twig'
                ])
            ->end()
        ;
    }
}
