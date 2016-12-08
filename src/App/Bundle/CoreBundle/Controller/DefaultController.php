<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Entity\Article;
use App\Bundle\CoreBundle\Form\CallUsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppCoreBundle:Pages:main.html.twig', [
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

    public function getCallUsAction(Request $request)
    {
        $form = $this->createForm(CallUsType::class, null, [
            'action' => $this->generateUrl('app_core_bundle_callback_call_us'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

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
            var_dump($form->getData());
            exit;
        }

        return $this->redirect($referer);
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
            'news' => $news[0],
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
