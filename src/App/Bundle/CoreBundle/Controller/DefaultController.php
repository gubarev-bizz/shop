<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Entity\Article;
use App\Bundle\CoreBundle\Form\CallUsType;
use App\Bundle\ShopBundle\Form\AddProductCartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine();
        $products = $em->getRepository('AppShopBundle:Product')
            ->findBy([
                'active' => true,
            ], [], 5);
        $addProductToCartForm = [];

        foreach ($products as $product) {
            $addProductToCartForm[$product->getId()] = $this->createForm(AddProductCartType::class, null, [
                'action' => $this->generateUrl('app_shop_bundle_cart_add_item'),
                'productId' => $product->getId(),
                'count' => 1,
            ])->createView();
        }

        return $this->render('AppCoreBundle:Pages:main.html.twig', [
            'products' => $products,
            'addProductToCartForm' => $addProductToCartForm,
        ]);
    }

    /**
     * @return Response
     */
    public function aboutAction()
    {
        return $this->render('AppCoreBundle:Pages:about.html.twig');
    }

    /**
     * @return Response
     */
    public function contactAction()
    {
        return $this->render('AppCoreBundle:Pages:contact.html.twig');
    }

    /**
     * @return Response
     */
    public function shippingAndPaymentAction()
    {
        return $this->render('AppCoreBundle:Pages:shippingPayment.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function feedbackAction(Request $request)
    {
        $form = $this->createForm(CallUsType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $this->get('visual_craft_mailer.mailer')->send('call_us', [
                'fullName' => $formData['name'],
                'phone' => $formData['phone'],
            ]);
            $this->addFlash('success', 'Your email was successfully sent.');

            return $this->redirectToRoute('app_core_bundle_page_feedback');
        }

        return $this->render('AppCoreBundle:Pages:feedback.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getSidebarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppCoreBundle:Category')->findBy([
            'parent' => null,
            'active' => true,
        ]);
        $activeCategory = null;

        if ($request->attributes->get('_route') === 'app_core_bundle_category_item') {
            $activeCategory = $em->getRepository('AppCoreBundle:Category')->findOneBy([
                'slug' => $request->attributes->get('slug'),
            ]);
        }

        return $this->render('AppCoreBundle:Layouts:sidebar.html.twig', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getCallUsAction(Request $request)
    {
        $form = $this->createForm(CallUsType::class, null, [
            'action' => $this->generateUrl('app_core_bundle_callback_call_us'),
            'method' => 'POST',
        ]);

        return $this->render('AppCoreBundle:Block:callUsBlock.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function processCallUsAction(Request $request)
    {
        $form = $this->createForm(CallUsType::class);
        $form->handleRequest($request);
        $referer = $request->headers->get('referer');

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $this->get('visual_craft_mailer.mailer')->send('call_us', [
                'fullName' => $formData['name'],
                'phone' => $formData['phone'],
            ]);
        }

        return $this->redirect($referer);
    }

    /**
     * @return Response
     */
    public function getLastNewsBlockAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppCoreBundle:Article')->findOneBy([
            'type' => Article::TYPE_NEWS
        ], [
            'createdAt' => 'DESC'
        ], 1);

        return $this->render('AppCoreBundle:Block:lastNewsBlock.html.twig', [
            'news' => $news,
        ]);
    }

    public function getMainCategoryBlockAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppCoreBundle:Category')->findBy([
            'active' => true,
            'mainPage' => true,
        ], [
            'title' => 'ASC'
        ]);

        return $this->render('AppCoreBundle:Block:mainCategoryBlock.html.twig', [
            'categories' => $categories,
        ]);
    }
}
