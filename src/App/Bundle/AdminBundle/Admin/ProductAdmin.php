<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Product;
use App\Bundle\CoreBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'products';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('code')
            ->add('title')
            ->add('category')
            ->add('manufacturer')
            ->add('country')
            ->add('price', null, [
                'template' => 'AppAdminBundle:Admin/List:list_price.html.twig'
            ])
            ->add('currency')
            ->add('priceUah', null, ['label' => 'Price UAH'])
            ->add('user')
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
        /** @var Product $object */
        $object = $this->getSubject();

        $formMapper
            ->with('Basic information')
                ->add('code', 'text', ['label' => 'SKU'])
                ->add('title', 'text', ['label' => 'Title'])
                ->add('content', 'sonata_simple_formatter_type', [
                    'format' => 'markdown',
                    'ckeditor_context' => 'default',
                ])
                ->add('category')
                ->add('price', 'number', [
                    'data' => ($object->getRealPrice()) ? $object->getRealPrice() : 1
                ])
                ->add('currency', 'choice', [
                    'choices' => [
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'UAH' => 'UAH',
                    ]
                ])
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        /** @var Product $object */
        $object->setPriceUah(null);
        $multiCurrency = $this->getConfigurationPool()->getContainer()->get('app_shop_bundle.multi_currency');

        if ($object->getCurrency() == 'USD') {
            $price = $multiCurrency->get('USD') * $object->getPrice();
            $object->setPriceUah($price);
        }

        if ($object->getCurrency() == 'EUR') {
            $price = $multiCurrency->get('EUR') * $object->getRealPrice();
            $object->setPriceUah($price);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        /** @var Product $object */
        $object = parent::getNewInstance();
        /** @var User $user */
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setUser($user);
        $object->setPriceUah(null);
        $multiCurrency = $this->getConfigurationPool()->getContainer()->get('app_shop_bundle.multi_currency');

        if ($object->getCurrency() == 'USD') {
            $price = $multiCurrency->get('USD') * $object->getPrice();
            $object->setPriceUah($price);
        }

        if ($object->getCurrency() == 'EUR') {
            $price = $multiCurrency->get('EUR') * $object->getRealPrice();
            $object->setPriceUah($price);
        }

        return $object;
    }
}
