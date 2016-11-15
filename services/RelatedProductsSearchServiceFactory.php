<?php

namespace app\services;

use app\services\{RelatedProductsSearchService,
    ServicesFactoryInterface};
use app\exceptions\ExceptionsTrait;

class RelatedProductsSearchServiceFactory implements ServicesFactoryInterface
{
    use ExceptionsTrait;
    
    public function getService(): SearchServiceInterface
    {
        try {
            return new RelatedProductsSearchService();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
