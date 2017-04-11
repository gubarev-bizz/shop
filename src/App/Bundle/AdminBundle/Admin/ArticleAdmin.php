<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Article;
use App\Bundle\CoreBundle\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Iphp\FileStoreBundle\Form\Type\FileType as IphpFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'content';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('type')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'show' => [],
                    'delete' => [],
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basic information')
            ->add('title', 'text', ['label' => 'Title'])
            ->add('imageFile', VichImageType::class, array(
                'required'      => false,
                'allow_delete'  => true,
                'download_link' => false,
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ))
            ->add('content', 'textarea', [
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => 'advanced',
                ],
            ])
            ->add('type', 'choice', [
                'choices' => [
                    'Article' => Article::TYPE_ARTICLE,
                    'News' => Article::TYPE_NEWS,
                    'Stock' => Article::TYPE_STOCK,
                ]
            ])
            ->end();
    }

//    /**
//     * @param Article $object
//     */
//    public function prePersist($object)
//    {
//        $this->preUpdate($object);
//        parent::prePersist($object);
//    }
//
//    /**
//     * @param Article $object
//     */
//    public function preUpdate($object)
//    {
//        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
//        $object->setImages($object->getImages());
//
//        if ($object->getId() !== null) {
//            foreach ($object->getImages() as $image) {
//                $image->setArticle($object);
//                $em->flush();
//            }
//        }
//
//        parent::preUpdate($object);
//    }
}
