<?php

namespace App\Bundle\ShopBundle\Controller;

use App\Bundle\ShopBundle\Form\AddProductCartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * @param int $id
     * @return Response
     */
    public function productItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppCoreBundle:Product')->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Product is not find.');
        }

        $addProductToCartForm = $this->createForm(AddProductCartType::class, null, [
            'action' => $this->generateUrl('app_shop_bundle_cart_add_item'),
            'productId' => $product->getId(),
        ]);

        return $this->render('AppShopBundle:Product:productItem.html.twig', [
            'product' => $product,
            'addProductToCartForm' => $addProductToCartForm->createView(),
        ]);
    }
}
