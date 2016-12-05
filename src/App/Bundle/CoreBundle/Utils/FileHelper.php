<?php

namespace App\Bundle\CoreBundle\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    /**
     * @var string
     */
    private $dirName;

    /**
     * @param string $dirName
     */
    public function __construct($dirName)
    {
        $this->dirName = $dirName;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = $this->generateFileName($file->getClientOriginalExtension());
        $file->move($this->dirName, $fileName);

        return $fileName;
    }

    public function remove($file)
    {
        if ($file !== null) {
//            $this->remove($file);
        }
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getPathNameByFileName($fileName)
    {
        $file = $this->dirName . '/' . $fileName;

        return $file;
    }

    /**
     * @param string $extension
     * @return string
     */
    private function generateFileName($extension)
    {
        return str_replace('.', '', uniqid('', true)) . '.' . $extension;
    }
}