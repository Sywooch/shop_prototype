<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repository\GetRelatedProductsRepositoryFactory;
use app\models\ProductsModel;
use app\services\SearchServiceInterface;

class RelatedProductsSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    public function search($productsModel): array
    {
        try {
            if (empty($productsModel) || !$productsModel instanceof ProductsModel) {
                throw new ErrorException(ExceptionsTrait::emptyError('ProductsModel'));
            }
            
            $repository = (new GetRelatedProductsRepositoryFactory())->getRepository();
            $array = $repository->getGroup($productsModel);
            
            return $array;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
