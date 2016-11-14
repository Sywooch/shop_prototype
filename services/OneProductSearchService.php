<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repository\GetOneProductRepositoryFactory;
use app\models\ProductsModel;
use app\services\SearchServiceInterface;

class OneProductSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    public function search($request): ProductsModel
    {
        try {
            if (empty($seocode = $request[\Yii::$app->params['productKey']])) {
                throw new ErrorException(ExceptionsTrait::emptyError(\Yii::$app->params['productKey']));
            }
            
            $repository = (new GetOneProductRepositoryFactory())->getRepository();
            $model = $repository->getOne($seocode);
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$model'));
            }
            
            return $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
