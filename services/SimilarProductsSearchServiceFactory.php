<?php

namespace app\services;

use app\services\ServicesFactoryInterface;
use app\exceptions\ExceptionsTrait;

class SimilarProductsSearchServiceFactory implements ServicesFactoryInterface
{
    use ExceptionsTrait;
    
    public function getService(): SearchServiceInterface
    {
        try {
            return new SimilarProductsSearchService();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
