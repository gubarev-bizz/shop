<?php

namespace App\Bundle\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ReviewAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'reviews';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('email')
            ->add('approve')
            ->add('createdAt')
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
                ->add('name')
                ->add('email')
                ->add('content', 'textarea', [
                    'attr' => [
                        'class' => 'tinymce',
                        'data-theme' => 'advanced',
                    ],
                ])
                ->add('product')
                ->add('approve')
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('product', null, [
                'show_filter' => true,
            ])
        ;
    }
}
