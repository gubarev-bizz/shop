<?php

namespace App\Bundle\AdminBundle\Controller;

use App\Bundle\AdminBundle\Form\UploadProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ParserController extends Controller
{
    public function parserAction(Request $request)
    {
        $form = $this->createForm(UploadProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $this->get('app_admin.parser.file');
            $fileName = $file->uploadFile($form->getData()['file']);
            $filePath = $file->getPathNameByFileName($fileName);
            $this->get('app_admin.parser')->parser($filePath);
            $this->get('app_shop_bundle.multi_currency')->refreshCurrency();

            $this->addFlash('success', 'Products have been successfully added.');
        }

        return $this->render('AppAdminBundle:Admin/Page:parser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
