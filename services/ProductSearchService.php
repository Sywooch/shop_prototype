<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repository\GetOneRepositoryInterface;
use app\models\ProductsModel;
use app\services\SearchServiceInterface;

class ProductSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    private $repository;
    
    public function __construct(GetOneRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function search($request): ProductsModel
    {
        try {
            if (empty($seocode = $request[\Yii::$app->params['productKey']])) {
                throw new ErrorException(ExceptionsTrait::emptyError(\Yii::$app->params['productKey']));
            }
            
            $model = $this->repository->getOne($seocode);
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$model'));
            }
            
            return $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
