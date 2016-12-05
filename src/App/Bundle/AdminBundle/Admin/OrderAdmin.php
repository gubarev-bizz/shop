<?php

namespace App\Bundle\AdminBundle\Admin;

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
            ->add('createdAt', 'date', [
                'label' => 'Created',
                'format' => 'd-m-Y H:j',
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
                ->add('status')
                ->add('products', null, [
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
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
        $original = $em->getUnitOfWork()->getOriginalEntityData($object);

        if ($original['status'] != $this->getSubject()->getStatus()) {
            $this->mailer->send('change_status_order', [
                'order' => $object,
            ]);
        }
    }
}
