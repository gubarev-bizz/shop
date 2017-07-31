<?php

namespace App\Bundle\ShopBundle\Controller;

use App\Bundle\ShopBundle\Controller\Traits\Referer;
use App\Bundle\ShopBundle\Form\AddProductCartType;
use App\Bundle\ShopBundle\Form\RemoveProductInCartType;
use App\Bundle\ShopBundle\Form\UpdateProductInCartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    use Referer;

    /**
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function addProductAction(Request $request)
    {
        $productCartForm = $this->createForm(AddProductCartType::class, null);
        $productCartForm->handleRequest($request);

        if ($productCartForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $formData = $productCartForm->getData();
            $product = $em->getRepository('AppShopBundle:Product')->find($formData['productId']);
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
                        'price' => $product->getPrice() * (int) $formData['count'],
                    ];
                    $session->set('cartElements', $productsCart);
                }
            }

            return $this->redirectToRoute('app_show_bundle_product_item', [
                'slug' => $product->getSlug(),
            ]);
        }

        return $this->redirectToRoute('app_core_bundle_page_main');
    }

//    /**
//     * @param Request $request
//     *
//     * @return JsonResponse|Response
//     */
//    public function addProductAction(Request $request)
//    {
//        $productCartForm = $this->createForm(AddProductCartType::class, null);
//        $productCartForm->handleRequest($request);
//
//        if ($productCartForm->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $formData = $productCartForm->getData();
//            $product = $em->getRepository('AppShopBundle:Product')->find($formData['productId']);
//            $session = $request->getSession();
//
//            if (!$product) {
//                throw new NotFoundHttpException('This product has been not found');
//            }
//
//            $formData['count'] = ($formData['count'] < 1) ? 1 : $formData['count'];
//
//            if(!$session->has('cartElements')) {
//                $session->set('cartElements', [
//                    $product->getId() => [
//                        'count' => $formData['count'],
//                        'price' => $product->getPrice(),
//                    ]
//                ]);
//            } else {
//                $productsCart = $session->get('cartElements');
//
//                if (isset($productsCart[$product->getId()])) {
//                    $productsCart[$product->getId()]['count'] += $formData['count'];
//                    $productsCart[$product->getId()]['price'] = $productsCart[$product->getId()]['count'] * $product->getPrice();
//                    $session->set('cartElements', $productsCart);
//                } else {
//                    $productsCart[$product->getId()] = [
//                        'count' => $formData['count'],
//                        'price' => $product->getPrice() * (int) $formData['count'],
//                    ];
//                    $session->set('cartElements', $productsCart);
//                }
//            }
//
//            $serializer = $this->get('jms_serializer');
//            $translator = $this->get('translator');
//            $response = $serializer->serialize([
//                'code' => 200,
//                'status' => 'OK',
//                'message' => $translator->trans('Product added to cart'),
//                'messageStatus' => 'success',
//            ], 'json');
//
//            return new Response($response);
//        }
//
//        return new JsonResponse([
//            'code' => 403,
//            'status' => 'Form invalid',
//        ]);
//    }

    /**
     * @return JsonResponse
     */
    public function getProductsOfCartAction()
    {
        $cart = $this->get('app_shop_bundle.cart')->getCart();

        return new JsonResponse([
            'code' => 200,
            'status' => 'OK',
            'data' => $cart,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function removeProductAction(Request $request)
    {
        $form = $this->createForm(RemoveProductInCartType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('AppShopBundle:Product')->find($formData['productId']);

            if ($product) {
                $cartElements = $request->getSession()->get('cartElements');
                unset($cartElements[$product->getId()]);
                $request->getSession()->set('cartElements', $cartElements);
                $this->addFlash('success', 'Product successfully deleted from cart');
            }
        }

        return $this->redirectToRoute('app_shop_bundle_cart');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProductInCartAction(Request $request)
    {
        $form = $this->createForm(UpdateProductInCartType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $productRepository = $this->getDoctrine()->getRepository('AppShopBundle:Product');
            $formData = $form->getData();
            $cartElements = $request->getSession()->get('cartElements');

            if (count($cartElements)) {
                foreach ($cartElements as $productId => $cartElement) {
                    if ($productId == $formData['productId']) {
                        if ($formData['count'] == 0) {
                            unset($cartElements[$productId]);
                            break;
                        }

                        $product = $productRepository->find($productId);

                        if ($product !== null) {
                            $cartElements[$productId]['price'] = $formData['count'] * $product->getPrice();
                            $cartElements[$productId]['count'] = $formData['count'];
                        } else {
                            unset($cartElements[$productId]);
                            break;
                        }
                    }
                }

                $request->getSession()->set('cartElements', $cartElements);
                $this->addFlash('success', 'Корзина обновлена.');
            }

            return $this->redirectToRoute('app_shop_bundle_cart');
        }

        return $this->redirectToRoute('app_shop_bundle_cart');
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
                $product = $em->getRepository('AppShopBundle:Product')->find($productId);

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
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('Cart', "app_shop_bundle_cart");
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");
        $productRepository = $this->getDoctrine()->getRepository('AppShopBundle:Product');
        $removeProductCartForms = [];
        $updateProductCartForms = [];

        foreach ($cart['elements'] as $key => $element) {
            $product = $productRepository->find($element['product']['id']);

            if ($product !== null) {
                $cart['elements'][$key]['object'] = $product;
                $removeProductCartForms[$product->getId()] = $this->createForm(RemoveProductInCartType::class, null, [
                    'action' => $this->generateUrl('app_shop_bundle_cart_remove_item'),
                ])->createView();
                $updateProductCartForms[$product->getId()] = $this->createForm(UpdateProductInCartType::class, null, [
                    'action' => $this->generateUrl('app_shop_bundle_cart_update_product'),
                ])->createView();
            }
        }

        return $this->render('AppShopBundle:Cart:cart.html.twig', [
            'cart' => $cart,
            'removeProductCartForms' => $removeProductCartForms,
            'updateProductCartForms' => $updateProductCartForms,
        ]);
    }

    /**
     * @return Response
     */
    public function cartBlockAction()
    {
        $cart = $this->get('app_shop_bundle.cart')->getCart();
        $countItems = 0;

        foreach ($cart['elements'] as $element) {
            $countItems += $element['count'];
        }

        return $this->render('AppShopBundle:Cart:cartBlock.html.twig', [
            'cart' => $cart,
            'countItems' => $countItems,
        ]);
    }
}
