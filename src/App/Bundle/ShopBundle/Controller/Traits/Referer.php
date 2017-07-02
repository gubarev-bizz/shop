<?php

namespace App\Bundle\ShopBundle\Controller\Traits;

use Symfony\Component\HttpFoundation\Request;

trait Referer
{
    /**
     * @param Request $request
     * @return mixed
     */
    private function getRefererParams(Request $request) {
        $referer = $request->headers->get('referer');
        $baseUrl = $request->getBaseUrl();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));

        return $this->get('router')->getMatcher()->match($lastPath);
    }
}
