<?php

namespace App\Bundle\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('mainPage')
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
            ->tab('Basic information')
                ->add('title', 'text', ['label' => 'Title'])
                ->add('parent', 'sonata_type_model', [
                    'required' => false,
                    'multiple' => false,
                    'btn_add' => false,
                    'property' => 'title',
                    'label' => 'Category',
                ])
                ->add('imageFile', VichImageType::class, array(
                    'required'      => false,
                    'allow_delete'  => true,
                    'download_link' => false,
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                ))
                ->add('mainPage')
                ->add('active')
            ->end()
            ->end()
            ->tab('SEO')
                ->add('seoDescription', 'textarea', [
                    'required' => false,
                ])
                ->add('seoTags', 'textarea', [
                    'required' => false,
                ])
            ->end()
        ;
    }
}
