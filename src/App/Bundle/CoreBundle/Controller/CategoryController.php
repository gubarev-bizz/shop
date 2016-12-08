<?php

namespace App\Bundle\CoreBundle\Controller;

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

        $entities = $em->getRepository('AppCoreBundle:Product')->findBy([
            'category' => $categoryId,
        ], [
            'createdAt' => 'DESC'
        ]);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities, $request->query->get('page', 1), $this->getParameter('paginator'));

        return $this->render('AppCoreBundle:Category:item.html.twig', [
            'category' => $category,
            'entities' => $pagination,
        ]);
    }
}
