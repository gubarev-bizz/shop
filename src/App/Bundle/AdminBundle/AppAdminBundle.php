<?php

namespace App\Bundle\AdminBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Bundle\AdminBundle\DependencyInjection\AppAdminBundleExtension;

class AppAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new AppAdminBundleExtension();
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'SonataAdminBundle';
    }
}
