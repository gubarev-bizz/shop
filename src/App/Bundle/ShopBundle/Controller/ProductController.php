<?php

namespace App\Bundle\ShopBundle\Controller;

use App\Bundle\CoreBundle\Entity\Review;
use App\Bundle\CoreBundle\Form\ReviewProductType;
use App\Bundle\ShopBundle\Form\AddProductCartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function productItemAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppShopBundle:Product')->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Product is not find.');
        }

        $addProductToCartForm = $this->createForm(AddProductCartType::class, null, [
            'action' => $this->generateUrl('app_shop_bundle_cart_add_item'),
            'productId' => $product->getId(),
        ]);
        $similarProducts = $em->getRepository('AppShopBundle:Product')
            ->findSimilarProductsByCategory($product, $product->getCategory())
        ;
        $addProductToCartFormSimilar = [];

        if ($similarProducts !== null) {
            foreach ($similarProducts as $entity) {
                $addProductToCartFormSimilar[$entity->getId()] = $this->createForm(AddProductCartType::class, null, [
                    'action' => $this->generateUrl('app_shop_bundle_cart_add_item'),
                    'productId' => $entity->getId(),
                    'count' => 1,
                ])->createView();
            }
        }

        $review = new Review();
        $review->setProduct($product);
        $reviewForm = $this->createForm(ReviewProductType::class, $review);
        $reviewForm->add('Add', SubmitType::class);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            $em->persist($review);
            $em->flush();
        }

        return $this->render('AppShopBundle:Product:productItem.html.twig', [
            'product' => $product,
            'similarProducts' => $similarProducts,
            'reviewForm' => $reviewForm->createView(),
            'addProductToCartForm' => $addProductToCartForm->createView(),
            'similarAddProductToCartForm' => $addProductToCartFormSimilar,
        ]);
    }
}
