<?php

namespace App\Bundle\CoreBundle\Twig\Extension;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class SEOExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return object|TwigEngine
     */
    public function getTemplating()
    {
        return $this->container->get('templating');
    }

    /**
     * @param string $parameter
     *
     * @return mixed
     */
    public function getParameter($parameter)
    {
        return $this->container->getParameter($parameter);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'seo_render' => new \Twig_SimpleFunction(
                'seo_render',
                array($this, 'seoRender'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * @param string|null $seoDescription
     * @param string|null $seoTags
     * @param string|null $title
     * @param Request $request
     *
     * @return string
     */
    public function seoRender($seoDescription = null, $seoTags = null, $title = null, Request $request)
    {

        return $this->getTemplating()->render('AppCoreBundle:SEO:index.html.twig', [
            'options' => [
                'description' => $seoDescription,
                'tags' => $seoTags,
                'siteName' => $this->getParameter('site_name'),
                'title' => $title,
                'request' => $request,
            ],
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'seo';
    }
}
