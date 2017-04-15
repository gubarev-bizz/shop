<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\ShopBundle\Entity\ProductItem;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                    'required' => true,
                    'label' => 'Code',
                    'disabled' => true,
                ])
                ->add('title', 'text', [
                    'required' => true,
                    'label' => 'Title',
                    'disabled' => true,
                ])
                ->add('price', 'number', [
                    'required' => true,
                    'label' => 'Price',
                    'disabled' => true,
                ])
                ->add('quantity', 'number', [
                    'label' => 'Quantity',
                    'required' => true,
                    'attr' => [
                        'min' => '1',
                    ]
                ])
            ->end()
        ;
    }
}
