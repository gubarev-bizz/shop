<?php

namespace App\Bundle\AdminBundle\Admin;

use App\Bundle\CoreBundle\Entity\Article;
use App\Bundle\CoreBundle\Form\UploadType as UploadImage;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use VisualCraft\Bundle\UploadFileBundle\Form\Type\UploadType;

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
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basic information')
                ->add('title', 'text', ['label' => 'Title'])
                ->add('content', 'sonata_simple_formatter_type', [
                    'format' => 'markdown',
                    'ckeditor_context' => 'default',
                ])
                ->add('type', 'choice', [
                    'choices' => [
                        'Article' => Article::TYPE_ARTICLE,
                        'News' => Article::TYPE_NEWS,
                        'Stock' => Article::TYPE_STOCK,
                    ]
                ])
            ->end()
        ;
    }
}
