<?php

namespace App\Bundle\ShopBundle\Form;

use App\Bundle\ShopBundle\Entity\DTO\ProductCountDTO;
use App\Bundle\ShopBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCountDTOType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class, [
                'required' => true,
            ])
            ->add('product', EntityType::class, [
                'required' => true,
                'class' => 'AppShopBundle:Product',
//                'choices' => $this->getProducts(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => ProductCountDTO::class,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'app_shop_bundle_product_count_dto';
    }

    /**
     * @return array
     */
    private function getProducts()
    {
        $products = $this->em->getRepository('AppShopBundle:Product')->findAll();
        $data = [];

        foreach ($products as $product) {
            $data[$product->getId()] = 'SKU: ' . $product->getCode() . '; Title: ' . $product->getTitle();
        }

        return $data;
    }
}
