<?php

namespace App\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SliderController extends Controller
{
    public function getSliderBlockAction()
    {
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository('AppCoreBundle:Slider')->findAll();

        return $this->render('AppCoreBundle:Block:sliderBlock.html.twig', [
            'sliders' => $slides
        ]);
    }
}
