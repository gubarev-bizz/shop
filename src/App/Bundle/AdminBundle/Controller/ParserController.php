<?php

namespace App\Bundle\AdminBundle\Controller;

use App\Bundle\AdminBundle\Form\UploadProductType;
use App\Bundle\ShopBundle\Entity\Import;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ParserController extends Controller
{
    public function parserAction(Request $request)
    {
        $form = $this->createForm(UploadProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $file = $this->get('app_admin.parser.file');
            $tmpFile = $form->getData()['file'];
            $fileName = $file->uploadFile($tmpFile);
            $filePath = $file->getPathNameByFileName($fileName);

            $import = new Import();
            $import->setFileName($tmpFile->getClientOriginalName());
            $import->setPath($filePath);
            $import->setStatus(Import::STATUS_INITIAL);
            $em->persist($import);
            $em->flush($import);

            $this->get('app_admin.parser')->parser($filePath);
            $this->get('app_shop_bundle.multi_currency')->refreshCurrency();

            $this->addFlash('success', 'Products have been successfully added.');
        }

        return $this->render('AppAdminBundle:Admin/Page:parser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
