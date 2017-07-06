<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function newsListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppCoreBundle:Article')->findBy([
            'type' => Article::TYPE_NEWS
        ], [
            'createdAt' => 'DESC'
        ]);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities, $request->query->get('page', 1), $this->getParameter('paginator'));

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('News', "app_core_bundle_article_news_list");
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Article:list.html.twig', [
            'entities' => $pagination
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function stockListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppCoreBundle:Article')->findBy([
            'type' => Article::TYPE_STOCK
        ], [
            'createdAt' => 'DESC'
        ]);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities, $request->query->get('page', 1), $this->getParameter('paginator'));

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('Promotions', "app_core_bundle_article_stock_list");
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Article:list.html.twig', [
            'entities' => $pagination
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function articleListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppCoreBundle:Article')->findBy([
            'type' => Article::TYPE_ARTICLE
        ], [
            'createdAt' => 'DESC'
        ]);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities, $request->query->get('page', 1), $this->getParameter('paginator'));

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('Articles', "app_core_bundle_article_article_list");
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Article:list.html.twig', [
            'entities' => $pagination
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    public function newsItemAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppCoreBundle:Article')->findOneBy([
            'slug' => $slug,
            'type' => Article::TYPE_NEWS,
        ]);

        if (!$entity) {
            throw new NotFoundHttpException('Article has not be found');
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('News', "app_core_bundle_article_news_list");
        $breadcrumbs->addRouteItem($entity->getTitle(), "app_core_bundle_article_news_item", [
            'slug' => $entity->getSlug(),
        ]);
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Article:item.html.twig', [
            'entity' => $entity
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    public function stockItemAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppCoreBundle:Article')->findOneBy([
            'slug' => $slug,
            'type' => Article::TYPE_STOCK,
        ]);

        if (!$entity) {
            throw new NotFoundHttpException('Article has not be found');
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('Promotions', "app_core_bundle_article_stock_list");
        $breadcrumbs->addRouteItem($entity->getTitle(), "app_core_bundle_article_stock_item", [
            'slug' => $entity->getSlug(),
        ]);
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Article:item.html.twig', [
            'entity' => $entity
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    public function articleItemAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppCoreBundle:Article')->findOneBy([
            'slug' => $slug,
            'type' => Article::TYPE_ARTICLE,
        ]);

        if (!$entity) {
            throw new NotFoundHttpException('Article has not be found');
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem('Articles', "app_core_bundle_article_article_list");
        $breadcrumbs->addRouteItem($entity->getTitle(), "app_core_bundle_article_article_item", [
            'slug' => $entity->getSlug(),
        ]);
        $breadcrumbs->prependRouteItem("Home", "app_core_bundle_page_main");

        return $this->render('AppCoreBundle:Article:item.html.twig', [
            'entity' => $entity
        ]);
    }
}
