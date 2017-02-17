<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Product;
use App\Bundle\ShopBundle\Entity\ItemOrder;
use App\Bundle\ShopBundle\Entity\Order;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
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
                ->add('items','sonata_type_collection', [
                    'required' => true,
                    'by_reference' => true,
                    'label' => 'Позиции заказа',
                    'btn_add' => 'Добавить',
                ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
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
                ->add('lock', null, [
                    'label' => 'Заблокировать'
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
            ->add('items.products', null, [
                'show_filter' => true,
                'label' => 'Продукты',
            ])
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
        $this->processAmountOrder($object);
    }

    /**
     * @param Order $object
     */
    public function postPersist($object)
    {
        parent::postPersist($object);
        $this->processAmountOrder($object);
    }

    /**
     * @param Order $object
     */
    public function prePersist($object)
    {
        parent::prePersist($object);
        $this->processAmountOrder($object);
    }

    /**
     * @param Order $object
     */
    public function processAmountOrder($object)
    {
        if (!$object->isLock()) {
            $orderAmount = 0;

            /** @var ItemOrder $item */
            foreach ($object->getItems() as $item) {
                /** @var Product[] $product */
                $product = $item->getProducts()->toArray();
                $item->setAmount($product[0]->getPriceUah() * $item->getQuantity());
                $item->setOriginalAmount($product[0]->getPriceUah());
                $orderAmount += $item->getAmount();
                $item->setOrder($object);
            }

            $object->setAmount($orderAmount);
        } else {
            $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
            $orderAmount = 0;

            /** @var ItemOrder $item */
            foreach ($object->getItems() as $item) {
                if ($item->getAmount() > 0) {
                    $item->setAmount($item->getOriginalAmount() * $item->getQuantity());
                } else {
                    /** @var Product[] $product */
                    $product = $item->getProducts();
                    $item->setAmount($product[0]->getPriceUah() * $item->getQuantity());
                }

                $orderAmount += $item->getAmount();

                if ($object->getId() !== null) {
                    $item->setOrder($object);
                }
            }

            $object->setAmount($orderAmount);
        }
    }
}
