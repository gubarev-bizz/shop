<?php

namespace App\Bundle\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ProductCountDTOAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basic information')
                ->add('quantity', 'number', [
                    'label' => 'Количество',
                    ])
                ->add('product')
            ->end()
        ;
    }
}
