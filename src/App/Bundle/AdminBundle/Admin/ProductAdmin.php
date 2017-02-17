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
            ->add('code', null, [
                'label' => 'SKU',
            ])
            ->add('title', null, [
                'label' => 'Наименование',
            ])
            ->add('category', null, [
                'label' => 'Категория',
            ])
            ->add('manufacturer', null, [
                'label' => 'Производитель',
            ])
            ->add('country', null, [
                'label' => 'Страна',
            ])
            ->add('price', null, [
                'label' => 'Цена',
                'template' => 'AppAdminBundle:Admin/List:list_price.html.twig',
            ])
            ->add('currency', null, [
                'label' => 'Валюта',
            ])
            ->add('priceUah', null, ['label' => 'Цена UAH'])
            ->add('user', null, ['label' => 'Пользователь'])
            ->add('top', null, ['label' => 'В топе'])
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
            ->tab('Основная информация')
                ->with('Основная информация')
                    ->add('code', 'text', ['label' => 'SKU'])
                    ->add('title', 'text', ['label' => 'Наименование'])
                    ->add('images','sonata_type_collection', [
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Изображения',
                    ], [
                            'edit' => 'inline',
                            'inline' => 'table',
                        ]
                    )
                    ->add('content', 'sonata_simple_formatter_type', [
                        'label' => 'Содержимое',
                        'format' => 'markdown',
                        'ckeditor_context' => 'default',
                    ])
                    ->add('category', null, [
                        'label' => 'Категория',
                        'required' => true,
                    ])
                    ->add('manufacturer', null, [
                        'required' => true,
                        'label' => 'Производитель',
                    ])
                    ->add('country', null, [
                        'required' => true,
                        'label' => 'Страна',
                    ])
                    ->add('price', 'number', [
                        'required' => true,
                        'label' => 'Цена',
                        'data' => ($object->getRealPrice()) ? $object->getRealPrice() : 1,
                    ])
                    ->add('currency', 'choice', [
                        'required' => true,
                        'label' => 'Валюта',
                        'choices' => [
                            'UAH' => 'UAH',
                            'USD' => 'USD',
                            'EUR' => 'EUR',
                        ],
                    ])
                    ->add('top', null, [
                        'label' => 'В топе',
                    ])
                ->end()
            ->end()
            ->tab('Атрибуты')
                ->with('Атрибуты')
                    ->add('ballType', 'text', [
                        'label' => 'Тип шара',
                        'required' => false,
                    ])
                    ->add('verticalBurdenBall', 'text', [
                        'label' => 'Вертикальная нагрузка на шар',
                        'required' => false,
                    ])
                    ->add('pullingBurdenBall', 'text', [
                        'label' => 'Тяговая нагрузка на шар',
                        'required' => false,
                    ])
                    ->add('installationCoordinationModule', 'text', [
                        'label' => 'Необходимость установки модуля согласования',
                        'required' => false,
                    ])
                    ->add('systemVoltage', 'text', [
                        'label' => 'Напряжение бортовой сети',
                        'required' => false,
                    ])
                    ->add('permissibleCurrentValues', 'text', [
                        'label' => 'Допустимое значения тока',
                        'required' => false,
                    ])
                    ->add('tractionLoad', 'text', [
                        'label' => 'Тяговая нагрузка',
                        'required' => false,
                    ])
                    ->add('removingBumper', 'text', [
                        'label' => 'Снятие бампера',
                        'required' => false,
                    ])
                    ->add('bumperCropping', 'text', [
                        'label' => 'Подрезка бампера',
                        'required' => false,
                    ])
                    ->add('needHarmonizeModule', 'text', [
                        'label' => 'Необходимость модуля согласования',
                        'required' => false,
                    ])
                    ->add('powerSocket', 'text', [
                        'label' => 'Розетка',
                        'required' => false,
                    ])
                    ->add('verticalLoad', 'text', [
                        'label' => 'Вертикальная нагрузка',
                        'required' => false,
                    ])
                ->end()
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
                'label' => 'Наименование товара',
                'show_filter' => true,
            ])
            ->add('category', null, [
                'label' => 'Категория',
                'show_filter' => true,
            ])
            ->add('manufacturer', null, [
                'label' => 'Производитель',
                'show_filter' => true,
            ])
            ->add('country', null, [
                'label' => 'Страна',
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
