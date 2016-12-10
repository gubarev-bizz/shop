<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Product;
use App\Bundle\ShopBundle\Entity\ItemOrder;
use App\Bundle\ShopBundle\Entity\Order;
use Sonata\AdminBundle\Admin\AbstractAdmin;
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
            ->add('id', 'text', ['label' => 'Order Id #'])
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('email')
            ->add('amount')
            ->add('status')
            ->add('createdAt', 'date', [
                'label' => 'Created',
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
                ->add('firstName', 'text', ['label' => 'First name'])
                ->add('lastName', 'text', ['label' => 'Last name'])
                ->add('phone', 'text', ['label' => 'Phone'])
                ->add('email', 'text', ['label' => 'Email'])
//                ->add('amount')
                ->add('items','sonata_type_collection', [
                    'required' => true,
                    'by_reference' => true,
                    'label' => 'Items Order',
                ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
                ->add('status', 'choice', [
                    'label' => 'Status',
                    'choices' => [
                        'AWAITING PAYMENT' => Order::AWAITING_PAYMENT,
                        'AWAITING SHIPMENT' => Order::AWAITING_SHIPMENT,
                        'TAKEN PROCESSING' => Order::TAKEN_PROCESSING,
                        'CANCELED' => Order::CANCELED,
                        'EXECUTED' => Order::EXECUTED,
                    ],
                ])
                ->add('lock', null, [
                    'label' => 'Lock order'
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
            ->with('Basic information')
                ->add('firstName')
                ->add('lastName')
                ->add('phone')
                ->add('email')
                ->add('amount')
                ->add('status')
                ->add('items', null, [
                    'template' => 'AppAdminBundle:Admin/Field:productOrder.html.twig'
                ])
            ->end()
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
