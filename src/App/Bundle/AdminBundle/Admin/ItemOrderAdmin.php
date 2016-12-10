<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\ShopBundle\Entity\ItemOrder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ItemOrderAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title')
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
//                ->add('amount', 'number', ['label' => 'Amount'])
                ->add('quantity', 'number', ['label' => 'Quantity'])
                ->add('products', null, ['label' => 'Product'])
            ->end()
        ;
    }
}
