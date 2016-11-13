<?php

namespace App\Bundle\CoreBundle\Utils;

use App\Bundle\CoreBundle\Entity\Article;
use App\Bundle\CoreBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private $fileHelper;

    private $em;

    /**
     * @param FileHelper $fileHelper
     * @param EntityManager $entityManager
     */
    public function __construct(FileHelper $fileHelper, EntityManager $entityManager)
    {
        $this->fileHelper = $fileHelper;
        $this->em = $entityManager;
    }

    /**
     * @param Article|Product $object
     * @param UploadedFile[] $files
     */
    public function persistFiles($object, $files)
    {
        if (count($files)) {
            $filesPersist = [];
//            $removeProcess = $this->removeFiles($object, $object->getImage());

                foreach ($files as $file) {
                    $filesPersist[] = $this->fileHelper->upload($file);
                }

                $object->setImage($filesPersist);
        }
    }

    /**
     * @param Article|Product $object
     * @param UploadedFile[] $files
     * @return bool|null
     */
    public function removeFiles($object, $files)
    {
        if (count($files)) {
            foreach ($files as $file) {
                $this->fileHelper->remove($file);
            }

            $object->setImage(null);

            return true;
        }

        return null;
    }
}
