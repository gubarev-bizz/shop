<?php

namespace App\Bundle\CoreBundle\Controller;

use App\Bundle\CoreBundle\Form\Auth\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @return Response
     */
    public function loginAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(
            LoginType::class,
            ['email' => $lastUsername],
            ['action' => $this->generateUrl('login_check')]
        );

        return $this->render('AppCoreBundle:Security:login.html.twig', [
            'form' => $form->createView(),
            'error'=> $error,
            'last_username' => $lastUsername,
        ]);
    }
}
