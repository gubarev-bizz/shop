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

class ParserProcess
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Factory
     */
    private $PHPExcel;

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @param EntityManager $entityManager
     * @param Factory $factoryExcel
     * @param string $uploadDir
     */
    public function __construct
    (
        EntityManager $entityManager,
        Factory $factoryExcel,
        $uploadDir
    ) {
        $this->em = $entityManager;
        $this->PHPExcel = $factoryExcel;
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

        $import->setStatus(Import::STATUS_PARSE_PROGRESS);
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

        $this->setCategoryImport($dataParse['categories'], $import);
        $this->setManufacturerImport($dataParse['production'], $import);
        $this->setCountryImport($dataParse['country'], $import);
        $this->setProductsImport($dataParse['products'], $import);

        $import->setStatus(Import::STATUS_INCOMPLETE);
        $this->em->flush($import);
    }

    /**
     * @param array $dataParse
     * @param Import $import
     */
    private function setProductsImport(array $dataParse, Import $import)
    {
        $products = $this->getUniqueProductsTitle();
        $import->setStatus(Import::STATUS_PRODUCT_PROGRESS);
        $this->em->flush($import);

        foreach ($dataParse as $item) {
            if (count($item) < 9) continue;

            if (!in_array($item['title'], $products) && isset($item['sku'])) {
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

                if (in_array($item['categoryId'], $this->getUniqueCategoriesImportId())) {
                    $category = $this->em->getRepository('AppCoreBundle:Category')->findOneBy([
                        'importId' => $item['categoryId']
                    ]);
                    $product->setCategory($category);
                }

                $product->setUser($import->getUser());
                $this->em->persist($product);

                $products[] = $item['title'];
            }
        }

        $this->em->flush();
    }

    /**
     * @return array
     */
    private function getUniqueProductsTitle()
    {
        $result = [];
        $categories = $this->em->getRepository('AppCoreBundle:Product')->findAll();

        foreach ($categories as $category) {
            if (!in_array($category->getTitle(), $result)) {
                $result[] = $category->getTitle();
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getUniqueCategoriesImportId()
    {
        $result = [];
        $categories = $this->em->getRepository('AppCoreBundle:Category')->findAll();

        foreach ($categories as $category) {
            if (!in_array($category->getImportId(), $result)) {
                $result[] = $category->getImportId();
            }
        }

        return $result;
    }

    /**
     * @return string[]
     */
    private function getUniqueCategoriesTitle()
    {
        $result = [];
        $categories = $this->em->getRepository('AppCoreBundle:Category')->findAll();

        foreach ($categories as $category) {
            if (!in_array($category->getTitle(), $result)) {
                $result[] = $category->getTitle();
            }
        }

        return $result;
    }

    /**
     * @param array $dataParse
     * @param Import $import
     */
    private function setCategoryImport(array $dataParse, Import $import)
    {
        $categories = $this->getUniqueCategoriesTitle();
        $import->setStatus(Import::STATUS_CATEGORY_PROGRESS);
        $this->em->flush($import);

        foreach ($dataParse as $item) {
            if (count($item) < 2) continue;

            if (!isset($item['parentId'])) {
                if (!in_array($item['title'], $categories)) {
                    $category = new Category();
                    $category->setTitle($item['title']);
                    $category->setImportId($item['categoryId']);
                    $this->em->persist($category);
                    $this->em->flush($category);
                    $categories[] = $item['title'];
                }
            } else {
                if (!in_array($item['title'], $categories)) {
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
                    $categories[] = $item['title'];
                }
            }
        }
    }

    /**
     * @param array $dataParse
     * @param Import $import
     */
    private function setManufacturerImport(array $dataParse, Import $import)
    {
        $manufacturies = $this->em->getRepository('AppCoreBundle:Manufacturer')->findAll();
        $manufacturiesArray = [];
        $import->setStatus(Import::STATUS_MANUFACTURER_PROGRESS);
        $this->em->flush($import);

        foreach ($manufacturies as $manufactury) {
            if (!in_array($manufactury->getTitle(), $manufacturiesArray)) {
                $manufacturiesArray[] = $manufactury->getTitle();
            }
        }

        foreach ($dataParse as $item) {
            if (!in_array($item, $manufacturiesArray)) {
                $manufacturer = new Manufacturer();
                $manufacturer->setTitle($item);
                $this->em->persist($manufacturer);
                $manufacturiesArray[] = $item;
            }
        }

        $this->em->flush();
    }

    /**
     * @param array $dataParse
     * @param Import $import
     */
    private function setCountryImport(array $dataParse, Import $import)
    {
        $countries = $this->em->getRepository('AppCoreBundle:Country')->findAll();
        $countriesArray = [];
        $import->setStatus(Import::STATUS_COUNTRY_PROGRESS);
        $this->em->flush($import);

        foreach ($countries as $country) {
            if (!in_array($country->getTitle(), $countriesArray)) {
                $countriesArray[] = $country->getTitle();
            }
        }

        foreach ($dataParse as $item) {
            if (!in_array($item, $countriesArray)) {
                $country = new Country();
                $country->setTitle($item);
                $this->em->persist($country);
                $countriesArray[] = $item;
            }
        }

        $this->em->flush();
    }
}
