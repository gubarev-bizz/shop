<?php

namespace App\Bundle\AdminBundle\Form\Sonata;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProductOrderFieldType extends AbstractType
{
    public function getParent()
    {
        return 'text';
    }

}
