<?php

namespace App\Bundle\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Iphp\FileStoreBundle\Form\Type\FileType as IphpFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageAdmin extends AbstractAdmin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'images';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
//            ->add('photoUpload', IphpFileType::class)
            ->add('imageFile', VichImageType::class, array(
                'required'      => false,
                'allow_delete'  => true,
                'download_link' => false,
            ))
        ;
    }
}
