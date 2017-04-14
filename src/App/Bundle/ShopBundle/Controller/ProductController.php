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

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem($product->getCategory()->getTitle(), "app_core_bundle_category_item", [
            'categoryId' => $product->getCategory()->getId(),
        ]);
        $breadcrumbs->addRouteItem($product->getTitle(), "app_show_bundle_product_item", [
            'id' => $product->getId(),
        ]);
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        $reviews = $em->getRepository('AppCoreBundle:Review')->findBy([
            'product' => $product,
            'approve' => true,
        ], [
            'createdAt' => 'DESC',
        ]);

        return $this->render('AppShopBundle:Product:productItem.html.twig', [
            'product' => $product,
            'reviews' => $reviews,
            'similarProducts' => $similarProducts,
            'reviewForm' => $reviewForm->createView(),
            'addProductToCartForm' => $addProductToCartForm->createView(),
            'similarAddProductToCartForm' => $addProductToCartFormSimilar,
        ]);
    }
}
