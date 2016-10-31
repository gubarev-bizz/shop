<?php

namespace App\Bundle\ShopBundle;

use App\Bundle\ShopBundle\DependencyInjection\AppShopBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppShopBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        return new AppShopBundleExtension();
    }
}
