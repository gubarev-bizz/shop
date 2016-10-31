<?php

namespace App\Bundle\AdminBundle\Controller;

use App\Bundle\AdminBundle\Form\MultiCurrencyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MultiCurrencyController extends Controller
{
    public function multiCurrencyAction(Request $request)
    {
        $form = $this->createForm(MultiCurrencyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $this->get('app_shop_bundle.multi_currency')->process('USD', $formData['usd']);
            $this->get('app_shop_bundle.multi_currency')->process('EUR', $formData['eur']);
            $this->get('app_shop_bundle.multi_currency')->refreshCurrency();
            $this->addFlash('success', 'Курс успешно был изменен');

            return $this->redirectToRoute('app_admin_bundle_multicurrency');
        }

        return $this->render('AppAdminBundle:Admin/Page:parser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
