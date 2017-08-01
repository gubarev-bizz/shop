<?php

namespace App\Bundle\ShopBundle\Controller;

use App\Bundle\ShopBundle\Entity\ProductItem;
use App\Bundle\ShopBundle\Entity\Order;
use App\Bundle\ShopBundle\Form\CheckoutType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function checkoutAction(Request $request)
    {
        $shopCart = $this->get('app_shop_bundle.cart');

        if ($shopCart->cartIsNull()) {
            return $this->redirectToRoute('app_shop_bundle_cart');
        }

        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $order = new Order();
        $form = $this->createForm(CheckoutType::class, $order);
        $form->add($translator->trans('CheckoutBtn'), SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-success',
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $order->setAmount($shopCart->getAmountCheckout());
            $order->setStatus(Order::STATUS_NEW);
            $em->persist($order);
            $em->flush();
            $productRepository = $em->getRepository('AppShopBundle:Product');

            foreach ($shopCart->getCartElements() as $productId => $item) {
                $product = $productRepository->find($productId);

                if ($product) {
                    $itemOrder = new ProductItem();
                    $itemOrder->setOrder($order);
                    $itemOrder->setCode($product->getCode());
                    $itemOrder->setContent($product->getContent());
                    $itemOrder->setCurrency($product->getCurrency());
                    $itemOrder->setPrice($product->getPrice());
                    $itemOrder->setPriceUah($product->getPriceUah());
                    $itemOrder->setTitle($product->getTitle());
                    $itemOrder->setQuantity((int) $item['count']);
                    $em->persist($itemOrder);
                }
            }

            $em->flush();
            $this->get('visual_craft_mailer.mailer')->send('change_status_order', [
                'order' => $order
            ]);
            $shopCart->setCartEmpty();

            return $this->redirectToRoute('app_show_bundle_checkout_show', ['id' => $order->getId()]);
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('Checkout', "app_shop_bundle_checkout");
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppShopBundle:Checkout:checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function checkoutShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('AppShopBundle:Order')->find($id);

        if (!$order) {
            throw new NotFoundHttpException('Order is not find.');
        }

        return $this->render('AppShopBundle:Checkout:checkoutShow.html.twig', [
            'order' => $order
        ]);
    }
}
