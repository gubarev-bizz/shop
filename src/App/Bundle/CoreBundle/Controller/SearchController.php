<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SearchType::class, null, [
            'search' => ($request->query->get('search')) ? $request->query->get('search') : null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        $resultArticle = $em->getRepository('AppCoreBundle:Article')->findAll();
        $resultProducts = $em->getRepository('AppCoreBundle:Product')->findAll();
        $result = $resultArticle + $resultProducts;
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($result, $request->query->get('page', 1), $this->getParameter('paginator'));

        return $this->render('AppCoreBundle:Pages:search.html.twig', [
            'form' => $form->createView(),
            'results' => $pagination,
        ]);
    }
}
