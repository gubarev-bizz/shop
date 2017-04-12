<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\ShopBundle\Entity\DTO\ProductCountDTO;
use App\Bundle\ShopBundle\Entity\Product;
use App\Bundle\ShopBundle\Entity\ProductItem;
use App\Bundle\ShopBundle\Entity\Order;
use App\Bundle\ShopBundle\Form\ProductCountDTOType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use VisualCraft\Bundle\MailerBundle\Mailer;

class OrderAdmin extends AbstractAdmin
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @param Mailer $mailer
     */
    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'orders';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', 'text', ['label' => '№ Заказа'])
            ->add('firstName', 'text', ['label' => 'Имя'])
            ->add('lastName', 'text', ['label' => 'Фамилия'])
            ->add('phone', 'text', ['label' => 'Телефон'])
            ->add('email', 'text', ['label' => 'Email'])
            ->add('amount', 'text', ['label' => 'Сумма'])
            ->add('status', 'choice', [
                'label' => 'Статус',
                'choices' => [
                    Order::AWAITING_PAYMENT => 'Ожидает оплаты',
                    Order::AWAITING_SHIPMENT => 'Ожидает отправки' ,
                    Order::TAKEN_PROCESSING => 'Принят в обработку',
                    Order::CANCELED => 'Отменен',
                    Order::EXECUTED => 'Выполнен',
                ]
            ])
            ->add('createdAt', 'date', [
                'label' => 'Дата создания',
                'format' => 'd-m-Y H:i',
            ])
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
            ->with('Basic information')
                ->add('firstName', 'text', ['label' => 'Имя'])
                ->add('lastName', 'text', ['label' => 'Фамилия'])
                ->add('phone', 'text', ['label' => 'Телефон'])
                ->add('email', 'text', ['label' => 'Email'])
                ->add('productItems', 'sonata_type_collection',
                    [
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Позиции заказа',
                        'btn_add' => false,
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
                ->add('products', CollectionType::class, [
                    'required' => false,
                    'label' => 'Add product',
                    'entry_type' => ProductCountDTOType::class,
                    'allow_add' => true,
                ])
                ->add('status', 'choice', [
                    'label' => 'Status',
                    'choices' => [
                        'Ожидает оплаты' => Order::AWAITING_PAYMENT,
                        'Ожидает отправки' => Order::AWAITING_SHIPMENT,
                        'Принят в обработку' => Order::TAKEN_PROCESSING,
                        'Отменен' => Order::CANCELED,
                        'Выполнен' => Order::EXECUTED,
                    ],
                ])
                ->add('delivery', 'choice', [
                    'label' => 'Delivery',
                    'choices' => [
                        'Автолюкс' => Order::DELIVERY_AUTOLUX,
                        'Деливери' => Order::DELIVERY_DELIVERY,
                        'Гюнсел' => Order::DELIVERY_GUNSEL,
                        'Интайм' => Order::DELIVERY_INTIME,
                        'Ночной экспресс' => Order::DELIVERY_NIGHT_EXPRESS,
                        'Новая Почта' => Order::DELIVERY_NOVA_POSHTA,
                    ],
                ])
                ->add('delivery', 'choice', [
                    'label' => 'Delivery',
                    'choices' => [
                        'Автолюкс' => Order::DELIVERY_AUTOLUX,
                        'Деливери' => Order::DELIVERY_DELIVERY,
                        'Гюнсел' => Order::DELIVERY_GUNSEL,
                        'Интайм' => Order::DELIVERY_INTIME,
                        'Ночной экспресс' => Order::DELIVERY_NIGHT_EXPRESS,
                        'Новая Почта' => Order::DELIVERY_NOVA_POSHTA,
                    ],
                ])
                ->add('paymentType', 'choice', [
                    'label' => 'Payment type',
                    'choices' => [
                        'Наложенный платеж' => Order::PAYMENT_TYPE_CASH_DELIVERY,
                        'Безналичный расчет' => Order::PAYMENT_TYPE_CASHLESS_PAYMENTS,
                    ],
                ])
            ->end()
        ;
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->with('Основная информация')
                ->add('firstName', null, [
                    'label' => 'Имя',
                ])
                ->add('lastName', null, [
                    'label' => 'Фамилия',
                ])
                ->add('phone', null, [
                    'label' => 'Телефон',
                ])
                ->add('email', null, [
                    'label' => 'Email',
                ])
                ->add('amount', null, [
                    'label' => 'Сумма',
                ])
                ->add('status', null, [
                    'label' => 'Статус',
                ])
                ->add('Позиции заказа', null, [
                    'template' => 'AppAdminBundle:Admin/Field:productOrder.html.twig'
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
            ->add('status', null, [
                'show_filter' => true,
                'label' => 'Статус',
            ], 'choice', [
                'choices' => [
                    'Ожидает оплаты' => Order::AWAITING_PAYMENT,
                    'Ожидает отправки' => Order::AWAITING_SHIPMENT,
                    'Принят в обработку' => Order::TAKEN_PROCESSING,
                    'Отменен' => Order::CANCELED,
                    'Выполнен' => Order::EXECUTED,
                ],
            ])
            ->add('email', null, [
                'show_filter' => true,
                'label' => 'Email',
            ])
            ->add('createdAt', 'doctrine_orm_date_range', [
                'label' => 'Дата создания',
                'show_filter' => true,
                'field_type' => 'sonata_type_date_range_picker',
                'field_options' => array(
                    'field_options_start' => array(
                        'format' => 'dd-MM-y',
                        'label' => 'Начиная с',
                        'dp_default_date' => new \DateTime('now'),
                        'data' => new \DateTime('now'),
                    ),
                    'field_options_end' => array(
                        'label' => 'До',
                        'format' => 'dd-MM-y',
                        'dp_use_current' => true,
                        'dp_show_today' => true,
                        'dp_default_date' => new \DateTime('now'),
                        'data' => new \DateTime('now'),
                    )
                )
            ])
        ;
    }

    /**
     * @param Order $object
     */
    public function preUpdate($object)
    {
        parent::preUpdate($object);
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        $original = $em->getUnitOfWork()->getOriginalEntityData($object);

        if ($original['status'] != $this->getSubject()->getStatus()) {
            $this->mailer->send('change_status_order', [
                'order' => $object,
            ]);
        }

        $this->processAmountOrder($object);
    }

    /**
     * @param Order $object
     */
    public function postUpdate($object)
    {
        parent::postUpdate($object);
//        $this->processAmountOrder($object);
    }

    /**
     * @param Order $object
     */
    public function postPersist($object)
    {
        parent::postPersist($object);
//        $this->processAmountOrder($object);
    }

    /**
     * @param Order $object
     */
    public function prePersist($object)
    {
        parent::prePersist($object);

        if ($object->getAmount() === null) {
            $object->setAmount(0);
        }
    }

    /**
     * @param Order $object
     */
    public function processAmountOrder($object)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');

        if ($object->getProducts()) {
            foreach ($object->getProducts() as $productCount) {
                $product = $productCount->getProduct();
                $productItem = new ProductItem();
                $productItem->setOrder($object);
                $productItem->setCode($product->getCode());
                $productItem->setContent($product->getContent());
                $productItem->setCurrency($product->getCurrency());
                $productItem->setPrice($product->getPrice());
                $productItem->setPriceUah($product->getPriceUah());
                $productItem->setTitle($product->getTitle());
                $productItem->setQuantity((int) $productCount->getQuantity());
                $em->persist($productItem);
            }

            $em->flush();
        }

        $items = $object->getProductItems();
        $amount = 0.0;

        foreach ($items as $item) {
            $amount += (int) $item->getQuantity() * $item->getRealPrice();
        }

        $object->setAmount($amount);
        $em->flush($object);
    }
}
