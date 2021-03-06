<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Image;
use App\Bundle\CoreBundle\Entity\User;
use App\Bundle\ShopBundle\Entity\Product;
use Liip\ImagineBundle\Controller\ImagineController;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
                'label' => 'Code',
            ])
            ->add('title', null, [
                'label' => 'Title',
            ])
            ->add('category', null, [
                'label' => 'Category',
            ])
            ->add('manufacturer', null, [
                'label' => 'Manufacturer',
            ])
            ->add('country', null, [
                'label' => 'Country',
            ])
            ->add('price', null, [
                'label' => 'Price',
                'template' => 'AppAdminBundle:Admin/List:list_price.html.twig',
            ])
            ->add('currency', null, [
                'label' => 'Currency',
            ])
            ->add('priceUah', null, ['label' => 'Цена UAH'])
            ->add('user', null, ['label' => 'User'])
            ->add('top', null, ['label' => 'Top'])
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
                ->with('Основная информация', [
                        'class' => 'col-md-6',
                        'box_class' => 'box box-success',
                    ]
                )
                    ->add('code', 'text', ['label' => 'Code'])
                    ->add('title', 'text', ['label' => 'Title'])
                    ->add('slug', 'text', [
                        'required' => false,
                    ])
                    ->add('content', 'textarea', [
                        'label' => 'Content',
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'advanced',
                        ],
                    ])
                    ->add('category', null, [
                        'label' => 'Category',
                        'required' => true,
                    ])
                    ->add('manufacturer', null, [
                        'required' => true,
                        'label' => 'Manufacturer',
                    ])
                    ->add('country', null, [
                        'required' => true,
                        'label' => 'Country',
                    ])
                    ->add('price', 'number', [
                        'required' => true,
                        'label' => 'Price',
                        'data' => ($object->getRealPrice()) ? $object->getRealPrice() : 1,
                    ])
                    ->add('currency', 'choice', [
                        'required' => true,
                        'label' => 'Currency',
                        'choices' => [
                            'UAH' => 'UAH',
                            'USD' => 'USD',
                            'EUR' => 'EUR',
                        ],
                    ])
                    ->add('top', null, [
                        'label' => 'В топе',
                    ])
                    ->add('active', null, [
                        'required' => false,
                        'label' => 'Активный',
                    ])
                ->end()
                ->with('Изображения', [
                        'class' => 'col-md-6',
                        'box_class' => 'box box-success',
                    ]
                )
                    ->add('imageFile', VichImageType::class, array(
                        'required'      => false,
                        'allow_delete'  => true,
                        'download_link' => false,
                    ))
                    ->add('images','sonata_type_collection', [
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Images',
                    ], [
                            'edit' => 'inline',
                            'inline' => 'table',
                        ]
                    )
                ->end()
            ->end()
            ->tab('Атрибуты')
                    ->add('ballType', 'text', [
                        'label' => 'Тип шара',
                        'required' => false,
                    ])
                    ->add('weightTowbar', 'text', [
                        'label' => 'Вес фаркопа',
                        'required' => false,
                    ])
                    ->add('numberContacts', 'text', [
                        'label' => 'Количество контактов',
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

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('code', null, [
                'label' => 'Артикул',
                'show_filter' => true,
            ])
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

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        /** @var Product $object */
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
