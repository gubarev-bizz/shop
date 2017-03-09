<?php

namespace App\Bundle\ShopBundle\Controller;

use App\Bundle\ShopBundle\Form\AddProductCartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addProductAction(Request $request)
    {
        $productCartForm = $this->createForm(AddProductCartType::class, null);
        $productCartForm->handleRequest($request);

        if ($productCartForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $formData = $productCartForm->getData();
            $product = $em->getRepository('AppCoreBundle:Product')->find($formData['productId']);
            $session = $request->getSession();

            if (!$product) {
                throw new NotFoundHttpException('This product has been not found');
            }

            $formData['count'] = ($formData['count'] < 1) ? 1 : $formData['count'];

            if(!$session->has('cartElements')) {
                $session->set('cartElements', [
                    $product->getId() => [
                        'count' => $formData['count'],
                        'price' => $product->getPrice(),
                    ]
                ]);
            } else {
                $productsCart = $session->get('cartElements');

                if (isset($productsCart[$product->getId()])) {
                    $productsCart[$product->getId()]['count'] += $formData['count'];
                    $productsCart[$product->getId()]['price'] = $productsCart[$product->getId()]['count'] * $product->getPrice();
                    $session->set('cartElements', $productsCart);
                } else {
                    $productsCart[$product->getId()] = [
                        'count' => $formData['count'],
                        'price' => $product->getPrice(),
                    ];
                    $session->set('cartElements', $productsCart);
                }
            }

            $this->addFlash('success', 'Product added to cart');

//            return $this->redirectToRoute('app_show_bundle_product_item', [
//                'id' => $product->getId()
//            ]);
        }

//        return $this->redirectToRoute('app_core_bundle_page_main');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeProductAction(Request $request)
    {
        $productId = $request->request->get('productId');
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppCoreBundle:Product')->find($productId);

        if ($product) {
            $cartElements = $request->getSession()->get('cartElements');
            unset($cartElements[$product->getId()]);
            $request->getSession()->set('cartElements', $cartElements);

            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshCartAction(Request $request)
    {
        $elements = $request->request->get('count');

        if ($elements !== null) {
            $em = $this->getDoctrine()->getManager();
            $request->getSession()->set('cartElements', null);
            $productsCart = [];

            foreach ($elements as $productId => $count) {
                $product = $em->getRepository('AppCoreBundle:Product')->find($productId);

                if ($product) {
                    $count = ($count < 1) ? 1 : $count;
                    $productsCart[$product->getId()] = [
                        'count' => $count,
                        'price' => $count * $product->getPrice(),
                    ];
                }
            }

            $request->getSession()->set('cartElements', $productsCart);

            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function cartAction(Request $request)
    {
        $cart = $this->get('app_shop_bundle.cart')->getCart();

        return $this->render('AppShopBundle:Cart:cart.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @return Response
     */
    public function cartBlockAction()
    {
        $cart = $this->get('app_shop_bundle.cart')->getCart();

        return $this->render('AppShopBundle:Cart:cartBlock.html.twig', [
            'cart' => $cart,
        ]);
    }
}
