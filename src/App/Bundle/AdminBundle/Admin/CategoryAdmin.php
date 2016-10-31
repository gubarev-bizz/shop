<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Product;
use App\Bundle\CoreBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'categories';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title')
            ->add('parent')
            ->add('active')
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
                ->add('title', 'text', ['label' => 'Title'])
                ->add('parent', 'sonata_type_model', [
                    'required' => false,
                    'multiple' => false,
                    'btn_add' => false,
                    'property' => 'title',
                    'label' => 'Category',
                ])
                ->add('active')
            ->end()
        ;
    }
}
