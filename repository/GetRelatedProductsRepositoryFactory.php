<?php

namespace app\repository;

use app\repository\{GetRelatedProductsRepository,
    GetGroupRepositoryInterface,
    RepositoryFactoryInterface};
use app\exceptions\ExceptionsTrait;

class GetRelatedProductsRepositoryFactory implements RepositoryFactoryInterface
{
    use ExceptionsTrait;
    
    public function getRepository(): GetGroupRepositoryInterface
    {
        try {
            return new GetRelatedProductsRepository();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
