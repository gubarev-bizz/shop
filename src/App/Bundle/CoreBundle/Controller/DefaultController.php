<?php

namespace App\Bundle\CoreBundle\Controller;

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
}
