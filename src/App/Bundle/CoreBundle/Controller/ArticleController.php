<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    public function newsListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppCoreBundle:Article')->findBy([
            'type' => Article::TYPE_NEWS
        ], [
            'createdAt' => 'DESC'
        ]);

        return $this->render('AppCoreBundle:Article:list.html.twig', [
            'entities' => $entities
        ]);
    }

    public function stockListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppCoreBundle:Article')->findBy([
            'type' => Article::TYPE_STOCK
        ], [
            'createdAt' => 'DESC'
        ]);

        return $this->render('AppCoreBundle:Article:list.html.twig', [
            'entities' => $entities
        ]);
    }

    public function articleListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppCoreBundle:Article')->findBy([
            'type' => Article::TYPE_ARTICLE
        ], [
            'createdAt' => 'DESC'
        ]);

        return $this->render('AppCoreBundle:Article:list.html.twig', [
            'entities' => $entities
        ]);
    }

    public function newsItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppCoreBundle:Article')->findOneBy([
            'id' => $id,
            'type' => Article::TYPE_NEWS,
        ]);

        if (!$entity) {
            throw new NotFoundHttpException('Article has not be found');
        }

        return $this->render('AppCoreBundle:Article:item.html.twig', [
            'entity' => $entity
        ]);
    }

    public function stockItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppCoreBundle:Article')->findOneBy([
            'id' => $id,
            'type' => Article::TYPE_STOCK,
        ]);

        if (!$entity) {
            throw new NotFoundHttpException('Article has not be found');
        }

        return $this->render('AppCoreBundle:Article:item.html.twig', [
            'entity' => $entity
        ]);
    }

    public function articleItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppCoreBundle:Article')->findOneBy([
            'id' => $id,
            'type' => Article::TYPE_ARTICLE,
        ]);

        if (!$entity) {
            throw new NotFoundHttpException('Article has not be found');
        }

        return $this->render('AppCoreBundle:Article:item.html.twig', [
            'entity' => $entity
        ]);
    }
}
