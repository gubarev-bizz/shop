<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Entity\Review;
use App\Bundle\CoreBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function listAction(Request $request)
    {
        $doctrineManager = $this->getDoctrine()->getManager();
        $review = new Review();
        $review->setType(Review::REVIEW_TYPE_SITE);
        $form = $this->createForm(ReviewType::class, $review);
        $form->add('submit', 'submit', [
            'attr' => [
                'class' => 'btn-default btn',
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $doctrineManager->persist($review);
            $doctrineManager->flush();

            return $this->redirectToRoute('app_core_bundle_page_reviews');
        }

        $reviews = $doctrineManager->getRepository('AppCoreBundle:Review')
            ->findBy([
                'type' => Review::REVIEW_TYPE_SITE,
                'approve' => true,
            ], [
                'updatedAt' => 'DESC',
            ])
        ;

        return $this->render('AppCoreBundle:Review:list.html.twig', [
            'reviews' => $reviews,
            'form' => $form->createView(),
        ]);
    }
}
