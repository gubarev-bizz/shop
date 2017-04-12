<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\ShopBundle\Entity\ProductItem;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductItemAdmin extends AbstractAdmin
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
                ->add('code', 'text', [
                    'label' => 'SKU',
                    'disabled' => true,
                ])
                ->add('title', 'text', [
                    'label' => 'Title',
                    'disabled' => true,
                ])
                ->add('price', 'text', [
                    'label' => 'SKU',
                    'disabled' => true,
                ])
                ->add('quantity', 'number', ['label' => 'Количество'])
            ->end()
        ;
    }
}
