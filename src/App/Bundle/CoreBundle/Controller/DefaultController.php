<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    public function aboutAction()
    {
        return $this->render('AppCoreBundle:Pages:about.html.twig');
    }

    public function contactAction()
    {
        return $this->render('AppCoreBundle:Pages:contact.html.twig');
    }

    public function getSidebarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppCoreBundle:Category')->findBy([
            'parent' => null
        ]);

        return $this->render('AppCoreBundle:Layouts:sidebar.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function getLastNewsBlockAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppCoreBundle:Article')->findBy([
            'type' => Article::TYPE_NEWS
        ], [
            'createdAt' => 'DESC'
        ], 1);

        return $this->render('AppCoreBundle:Block:lastNewsBlock.html.twig', [
            'news' => $news,
        ]);
    }
}
