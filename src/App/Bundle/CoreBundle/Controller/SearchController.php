<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Form\SearchType;
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
        $form->add('submit', SubmitType::class, [
            'label' => 'Поиск',
            'attr' => [
                'class' => 'btn-success',
            ]
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

        return $this->render('AppCoreBundle:Pages:search.html.twig', [
            'results' => $pagination,
        ]);
    }
}
