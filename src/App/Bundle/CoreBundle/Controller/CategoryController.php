<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\ShopBundle\Form\AddProductCartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    public function categoryItemAction(Request $request, $categoryId)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppCoreBundle:Category')->find($categoryId);

        if (!$category) {
            throw new NotFoundHttpException('Category has not be found');
        }

        $entities = $em->getRepository('AppShopBundle:Product')->findBy([
            'category' => $categoryId,
        ], [
            'createdAt' => 'DESC'
        ]);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities, $request->query->get('page', 1), $this->getParameter('paginator'));
        $addProductToCartForm = [];

        foreach ($entities as $entity) {
            $addProductToCartForm[$entity->getId()] = $this->createForm(AddProductCartType::class, null, [
                'action' => $this->generateUrl('app_shop_bundle_cart_add_item'),
                'productId' => $entity->getId(),
                'count' => 1,
            ])->createView();
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem($category->getTitle(), "app_show_bundle_product_item", [
            'id' => $category->getId(),
        ]);
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Category:item.html.twig', [
            'category' => $category,
            'entities' => $pagination,
            'addProductToCartForm' => $addProductToCartForm,
        ]);
    }
}
