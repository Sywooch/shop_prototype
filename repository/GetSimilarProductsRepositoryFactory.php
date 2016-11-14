<?php

namespace app\repository;

use app\repository\{GetSimilarProductsRepository,
    GetGroupRepositoryInterface,
    RepositoryFactoryInterface};
use app\exceptions\ExceptionsTrait;

class GetSimilarProductsRepositoryFactory implements RepositoryFactoryInterface
{
    use ExceptionsTrait;
    
    public function getRepository(): GetGroupRepositoryInterface
    {
        try {
            return new GetSimilarProductsRepository();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
