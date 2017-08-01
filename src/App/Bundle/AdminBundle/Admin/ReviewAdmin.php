<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Review;
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
            ->add('type')
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
                ->add('type', 'choice', [
                    'choices' => array_combine([
                        Review::REVIEW_TYPE_PRODUCT, Review::REVIEW_TYPE_SITE
                    ], [
                        Review::REVIEW_TYPE_PRODUCT, Review::REVIEW_TYPE_SITE
                    ]),
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
