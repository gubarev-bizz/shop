<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Form\SearchType;
use App\Bundle\ShopBundle\Form\AddProductCartType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function searchFormAction(Request $request)
    {
        $form = $this->createForm(SearchType::class, null, [
            'action' => $this->generateUrl('app_core_bundle_page_search')
        ]);
        $form->handleRequest($request);

        return $this->render('AppCoreBundle:Block:searchBlock.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $resultArticle = $resultProducts = [];
        $data = $request->query->get('search');

        if (!empty($data) && $data['title'] !== null) {
            $resultArticle = $em->getRepository('AppCoreBundle:Article')
                ->searchByTitle($data['title'])
            ;
            $resultProducts = $em->getRepository('AppShopBundle:Product')
                ->searchByTitle($data['title'])
            ;
        }

        $result = $resultArticle + $resultProducts;
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($result, $request->query->get('page', 1), $this->getParameter('paginator'));
        $addProductCartTypeForms = [];

        foreach ($resultProducts as $product) {
            $addProductCartTypeForms[$product->getId()] = $this->createForm(AddProductCartType::class, null, [
                'productId' => $product->getId(),
                'count' => 1,
                'action' => $this->generateUrl('app_shop_bundle_cart_add_item')
            ])->createView();
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('Search', "app_core_bundle_page_search");
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Pages:search.html.twig', [
            'results' => $pagination,
            'addProductCartTypeForms' => $addProductCartTypeForms,
        ]);
    }
}
