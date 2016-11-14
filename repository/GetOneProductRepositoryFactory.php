<?php

namespace app\repository;

use app\repository\{GetOneProductRepository,
    GetOneRepositoryInterface,
    RepositoryFactoryInterface};
use app\exceptions\ExceptionsTrait;

class GetOneProductRepositoryFactory implements RepositoryFactoryInterface
{
    use ExceptionsTrait;
    
    public function getRepository(): GetOneRepositoryInterface
    {
        try {
            return new GetOneProductRepository();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
