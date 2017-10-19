<?php

namespace App\Bundle\AdminBundle\Controller;

use App\Bundle\AdminBundle\Model\DumpFile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DumpController extends Controller
{
    /**
     * @return string
     */
    private function getBasePath()
    {
        return $this->getParameter('kernel.root_dir') . '/../var/db_dump/';
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        $finder = new Finder();
        $finder->files()->in($this->getBasePath());
        $dumpFiles = new ArrayCollection();

        foreach ($finder as $file) {
            $dump = new DumpFile();
            $dump->setName($file->getRelativePathname());
            $dump->setPath($file->getRealPath());
            $dumpFiles->add($dump);
        }

        return $this->render('AppAdminBundle:Admin/List:list_dumps.html.twig', [
            'files' => $dumpFiles,
        ]);
    }

    /**
     * @param string $fileName
     * @return Response
     */
    public function downloadAction($fileName)
    {
        $basePath = $this->getBasePath() . $fileName;

        if (file_exists($basePath)) {
            $content = file_get_contents($basePath);
            $response = new Response();
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName);
            $response->setContent($content);

            return $response;
        }

        throw new NotFoundHttpException('File not found');
    }

    /**
     * @param string $fileName
     * @return RedirectResponse
     */
    public function deleteAction($fileName)
    {
        $filePath = $this->getBasePath() . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
            $this->addFlash('success', 'The file was successfully deleted.');

            return $this->redirectToRoute('app_admin_bundle_dump_list');
        }

        throw new NotFoundHttpException('File not found');
    }

    /**
     * @return RedirectResponse
     */
    public function createAction()
    {
        $application = new Application($this->get('kernel'));
        $application->setAutoExit(false);
        $input = new ArrayInput(["dizda:backup:start"]);
        $output = new ConsoleOutput();

        $retval = $application->run($input, $output);

        if(!$retval) {
            $this->addFlash('success', 'Command executed successfully!');
        } else {
            $this->addFlash('success', 'Command was not successful.');
        }

        return $this->redirectToRoute('app_admin_bundle_dump_list');
    }

    /**
     * @param string $fileName
     */
    public function importAction($fileName)
    {
        $filePath = $this->getBasePath() . $fileName;

    }
}
