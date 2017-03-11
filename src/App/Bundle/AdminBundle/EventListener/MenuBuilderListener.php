<?php

namespace App\Bundle\AdminBundle\EventListener;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class MenuBuilderListener
{
    public function addMenuItems(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        foreach ($menu->getChildren() as $child) {
            if ($child->getName() === 'Shop') {
                $child->addChild('Multi currency', [
                    'route' => 'app_admin_bundle_multicurrency',
                    'labelAttributes' => ['icon' => 'fa fa-bar-chart'],
                ]);
            }

            if ($child->getName() === 'Import') {
                $child->addChild('Import products', [
                    'route' => 'app_admin_bundle_parser',
                    'labelAttributes' => ['icon' => 'fa fa-bar-chart'],
                ]);
            }
        }
    }
}
