<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Image;
use App\Bundle\CoreBundle\Entity\Product;
use App\Bundle\CoreBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
                'template' => 'AppAdminBundle:Admin/List:list_price.html.twig',
            ])
            ->add('currency')
            ->add('priceUah', null, ['label' => 'Price UAH'])
            ->add('user')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'show' => [],
                    'delete' => [],
                ],
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
                ->add('images','sonata_type_collection', array(
                    'required' => false,
                    'by_reference' => false,
                    'label' => 'Media items',
                ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
                    )
                )
                ->add('content', 'sonata_simple_formatter_type', [
                    'format' => 'markdown',
                    'ckeditor_context' => 'default',
                ])
                ->add('category')
                ->add('manufacturer', null, [
                    'required' => true,
                ])
                ->add('country', null, [
                    'required' => true,
                ])
                ->add('price', 'number', [
                    'data' => ($object->getRealPrice()) ? $object->getRealPrice() : 1,
                ])
                ->add('currency', 'choice', [
                    'choices' => [
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'UAH' => 'UAH',
                    ],
                ])
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('title', null, [
                'show_filter' => true,
            ])
            ->add('category', null, [
                'show_filter' => true,
            ])
            ->add('manufacturer', null, [
                'show_filter' => true,
            ])
            ->add('country', null, [
                'show_filter' => true,
            ])
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

    public function postUpdate($object)
    {
        parent::postUpdate($object);

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        /** @var Image $image */
        foreach ($object->getImages() as $image) {
            if ($image->getProduct() != $object) {
                $image->setProduct($object);
                $em->persist($image);
                $em->flush();
            }
        }

        $em->persist($object);
        $em->flush();
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
