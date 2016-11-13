<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'users';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'show' => [],
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
                ->add('firstName', 'text', ['label' => 'First name'])
                ->add('lastName', 'text', ['label' => 'Last name'])
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        /** @var User $object */
        $object = parent::getNewInstance();
        $object->setRoles(['ROLE_MANAGER']);

        return $object;
    }
}
