<?php

namespace App\Bundle\AdminBundle\Controller;

use App\Bundle\AdminBundle\Form\UploadProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class ParserController extends Controller
{
    public function parserAction(Request $request)
    {
        $form = $this->createForm(UploadProductType::class);
        $form->handleRequest($request);

        $path = $this->getParameter('upload_dir') . '/../parser';
        $file = 'http://image.etov.ua/storage/640x640/c/0/e/2/c0e2e007a33913c0c5163f0ab46fba2d.jpg';
//        file_put_contents($path . '/dasas.jpg', file_get_contents($file));
//        preg_match_all("/\.gif|jpg|jpeg|png/", $file, $extension);

//        var_dump($extension[0][0]);
//        $ch = curl_init($file);
//        $fp = fopen($path . '/dasas.jpg', 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);

//        exit;

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $this->get('app_admin.parser.file');
            $fileName = $file->uploadFile($form->getData()['file']);
            $filePath = $file->getPathNameByFileName($fileName);
            $this->get('app_admin.parser')->parser($filePath);
            $this->get('app_shop_bundle.multi_currency')->refreshCurrency();
        }

        return $this->render('AppAdminBundle:Admin/Page:parser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
