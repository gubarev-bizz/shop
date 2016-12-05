<?php

namespace App\Bundle\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Iphp\FileStoreBundle\Form\Type\FileType as IphpFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SliderAdmin extends AbstractAdmin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'sliders';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('product')
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
            ->add('title')
            ->add('product', 'sonata_type_model', [
                'required' => true,
                'multiple' => false,
                'btn_add' => false,
                'property' => 'title',
                'label' => 'Product link',
            ])
            ->add('imageFile', VichImageType::class, array(
                'required'      => false,
                'allow_delete'  => true,
                'download_link' => false,
            ))
        ;
    }
}
