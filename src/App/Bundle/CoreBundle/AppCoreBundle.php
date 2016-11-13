<?php

namespace App\Bundle\CoreBundle;

use App\Bundle\CoreBundle\DependencyInjection\AppCoreBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppCoreBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new AppCoreBundleExtension();
    }
}
