<?php

namespace App\Bundle\AdminBundle\Parser;

use App\Bundle\CoreBundle\Entity\Category;
use App\Bundle\CoreBundle\Entity\Country;
use App\Bundle\CoreBundle\Entity\Image;
use App\Bundle\CoreBundle\Entity\Manufacturer;
use App\Bundle\CoreBundle\Entity\Product;
use App\Bundle\ShopBundle\Entity\Import;
use Doctrine\ORM\EntityManager;
use Liuggio\ExcelBundle\Factory;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Handler\DownloadHandler;

class ParserProcess
{
    private $em;

    private $PHPExcel;

    private $token;

    private $uploadDir;

    /**
     * @param EntityManager $entityManager
     * @param Factory $factoryExcel
     * @param TokenStorageInterface $tokenStorage
     * @param string $uploadDir
     */
    public function __construct
    (
        EntityManager $entityManager,
        Factory $factoryExcel,
        TokenStorageInterface $tokenStorage,
        $uploadDir
    )
    {
        $this->em = $entityManager;
        $this->PHPExcel = $factoryExcel;
        $this->token = $tokenStorage;
        $this->uploadDir = $uploadDir;
    }

    /**
     * @param int $importId
     * @param string $filePath
     */
    public function parser($importId, $filePath)
    {
        $import = $this->em->getRepository('AppShopBundle:Import')->find($importId);

        if (!$import) {
            return;
        }

        $import->setStatus(Import::STATUS_READY);
        $this->em->flush($import);

        /** @var \PHPExcel $phpExcelObject */
        $phpExcelObject = $this->PHPExcel->createPHPExcelObject($filePath);
        $dataParse = [];
        $dataParse['production'] = [];
        $dataParse['country'] = [];
        $dataParse['categories'] = [];

        foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
            if ($worksheet->getCodeName() === 'Worksheet') {
                foreach ($worksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $rowIndex = $row->getRowIndex();

                    /** @var \PHPExcel_Cell $cell */
                    foreach ($cellIterator as $key => $cell) {
                        if ($rowIndex > 1) {
                            if ($cell->getColumn() == 'A' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['sku'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'B' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['title'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'C' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['keywords'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'D' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['description'] = $cell->getFormattedValue();
                            }

                            if ($cell->getColumn() == 'E' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['price'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'L' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['images'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'F' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['currency'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'N' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['country'] = $cell->getValue();

                                if (!array_search($cell->getValue(), $dataParse['country'])) {
                                    $dataParse['country'][$rowIndex] = $cell->getValue();
                                }
                            }

                            if ($cell->getColumn() == 'O' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['categoryId'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'M' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['products'][$rowIndex]['production'] = $cell->getValue();

                                if (!array_search($cell->getValue(), $dataParse['production'])) {
                                    $dataParse['production'][$rowIndex] = $cell->getValue();
                                }
                            }
                        }
                    }
                }
            }

            if ($worksheet->getCodeName() === 'Worksheet_1') {
                foreach ($worksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $rowIndex = $row->getRowIndex();

                    /** @var \PHPExcel_Cell $cell */
                    foreach ($cellIterator as $key => $cell) {
                        if ($rowIndex > 1) {
                            if ($cell->getColumn() == 'A' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['categories'][$rowIndex]['categoryId'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'B' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['categories'][$rowIndex]['title'] = $cell->getValue();
                            }

                            if ($cell->getColumn() == 'C' && $cell->getValue() !== '' && $cell->getValue() !== null) {
                                $dataParse['categories'][$rowIndex]['parentId'] = $cell->getValue();
                            }
                        }
                    }
                }
            }
        }

        $this->setCategoryImport($dataParse['categories']);
        $this->setManufacturerImport($dataParse['production']);
        $this->setCountryImport($dataParse['country']);
        $this->setProductsImport($dataParse['products']);

        $import->setStatus(Import::STATUS_INCOMPLETE);
        $this->em->flush($import);
    }

    /**
     * @param array $dataParse
     */
    private function setProductsImport($dataParse)
    {
        foreach ($dataParse as $item) {
            if (count($item) < 9) continue;

            $product = $this->em->getRepository('AppCoreBundle:Product')->findOneBy([
                'title' => $item['title']
            ]);

            if (!$product) {

                $category = $this->em->getRepository('AppCoreBundle:Category')->findOneBy([
                    'importId' => $item['categoryId']
                ]);
                $manufacturer = $this->em->getRepository('AppCoreBundle:Manufacturer')->findOneBy([
                    'title' => $item['production']
                ]);
                $country = $this->em->getRepository('AppCoreBundle:Country')->findOneBy([
                    'title' => $item['country']
                ]);

                $product = new Product();
                $product->setCode($item['sku']);
                $product->setTitle($item['title']);
                $product->setContent($item['description']);
                $product->setCurrency($item['currency']);
                $product->setPrice($item['price']);

                if ($manufacturer) {
                    $product->setManufacturer($manufacturer);
                }

                if ($country) {
                    $product->setCountry($country);
                }

                if ($category) {
                    $product->setCategory($category);
                }

                $product->setUser($this->token->getToken()->getUser());
                $this->em->persist($product);
                $this->em->flush($product);
            }
        }

    }

    /**
     * @param string $images
     * @return array|bool
     */
    private function processSetImage($images)
    {
        if (trim($images) != '') {
            $imagesData = explode(',', $images);
            $imagesEntity = [];

            foreach ($imagesData as $image) {
                preg_match_all("/\.gif|jpg|jpeg|png/", $image, $extension);
                $fileName = md5(uniqid()) . '.' . $extension[0][0];
                $path = $this->uploadDir . '/' . $fileName;

                $this->downloadFile($image, $path);

//                file_put_contents($path, fopen($image, 'r'));

//                $ch = curl_init($image);
//                $fp = fopen($path, "wb");
//                curl_setopt($ch, CURLOPT_FILE, $fp);
//                curl_setopt($ch, CURLOPT_HEADER, 0);
//                curl_exec($ch);
//                curl_close($ch);
//                fclose($fp);

                $image = new Image();
                $image->setImageName($fileName);
                $this->em->persist($image);
                $this->em->flush();
                $imagesEntity[] = $image;
            }

            return $imagesEntity;
        }

        return false;
    }

    private function downloadFile($url, $path)
    {
        $newfname = $path;
        $file = fopen ($url, 'rb');
        if ($file) {
            $newf = fopen ($newfname, 'wb');
            if ($newf) {
                while(!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
    }

    /**
     * @param array $dataParse
     */
    private function setCategoryImport($dataParse)
    {
        foreach ($dataParse as $item) {
            if (count($item) < 2) continue;

            if (!isset($item['parentId'])) {
                $category = $this->em->getRepository('AppCoreBundle:Category')->findOneBy([
                    'title' => $item['title']
                ]);

                if (!$category) {
                    $category = new Category();
                    $category->setTitle($item['title']);
                    $category->setImportId($item['categoryId']);
                    $this->em->persist($category);
                    $this->em->flush($category);
                }
            } else {
                $category = $this->em->getRepository('AppCoreBundle:Category')->findOneBy([
                    'title' => $item['title']
                ]);

                if (!$category) {
                    $category = new Category();
                    $category->setTitle($item['title']);
                    $category->setImportId($item['categoryId']);

                    $parentCategory = $this->em->getRepository('AppCoreBundle:Category')->findOneBy([
                        'importId' => $item['parentId']
                    ]);

                    if ($parentCategory) {
                        $category->setParent($parentCategory);
                    }

                    $this->em->persist($category);
                    $this->em->flush($category);
                }
            }
        }
    }

    private function setManufacturerImport($dataParse)
    {
        foreach ($dataParse as $item) {
            $manufacturer = $this->em->getRepository('AppCoreBundle:Manufacturer')->findOneBy([
                'title' => $item
            ]);

            if (!$manufacturer) {
                $manufacturer = new Manufacturer();
                $manufacturer->setTitle($item);
                $this->em->persist($manufacturer);
            }
        }

        $this->em->flush();
    }

    private function setCountryImport($dataParse)
    {
        foreach ($dataParse as $item) {
            $country = $this->em->getRepository('AppCoreBundle:Country')->findOneBy([
                'title' => $item
            ]);

            if (!$country) {
                $country = new Country();
                $country->setTitle($item);
                $this->em->persist($country);
            }
        }

        $this->em->flush();
    }
}
