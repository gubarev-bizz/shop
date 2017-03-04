<?php

namespace App\Bundle\ShopBundle\Controller;

use App\Bundle\ShopBundle\Entity\ItemOrder;
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
     * @return RedirectResponse|Response
     */
    public function checkoutAction(Request $request)
    {
        $shopCart = $this->get('app_shop_bundle.cart');

        if ($shopCart->cartIsNull()) {
            return $this->redirectToRoute('app_shop_bundle_cart');
        }

        $em = $this->getDoctrine()->getManager();
        $order = new Order();
        $form = $this->createForm(CheckoutType::class, $order);
        $form->add('Оформить', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setAmount($shopCart->getAmountCheckout());
            $order->setStatus(Order::STATUS_NEW);
            $order->setLock(true);
            $em->persist($order);
            $em->flush();
            $products = [];
            $productRepository = $em->getRepository('AppCoreBundle:Product');

            foreach ($shopCart->getCartElements() as $productId => $item) {
                $products = $productRepository->find($productId);
                $itemOrder = new ItemOrder();
                $itemOrder->setOrder($order);
                $itemOrder->setAmount((float) $item['price']);
                $itemOrder->setQuantity((int) $item['count']);
                $itemOrder->setProducts([$products]);
                $em->persist($itemOrder);
            }

            $em->flush();
            $this->get('visual_craft_mailer.mailer')->send('change_status_order', [
                'order' => $order
            ]);
            $shopCart->setCartEmpty();

            return $this->redirectToRoute('app_show_bundle_checkout_show', ['id' => $order->getId()]);
        }

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
