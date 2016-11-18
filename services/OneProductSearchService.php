<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repository\GetOneRepositoryInterface;
use app\models\{ProductsModel,
    QueryCriteria};
use app\services\SearchServiceInterface;

class OneProductSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
     /**
     * @var object GetOneRepositoryInterface для поиска данных по запросу
     */
    private $repository;
    
    /**
     * Обрабатывает запрос на поиск 1 товара
     * @param object Request
     * @return ProductsModel
     */
    public function search($request): ProductsModel
    {
        try {
            if (empty($seocode = $request[\Yii::$app->params['productKey']])) {
                throw new ErrorException(ExceptionsTrait::emptyError(\Yii::$app->params['seocode']));
            }
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            
            
            $criteria = new QueryCriteria();
            $criteria->where(['seocode'=>$seocode]);
            $this->repository->setCriteria($criteria);
            $model = $this->repository->getOne($seocode);
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
            
            return $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает GetOneRepositoryInterface свойству ProductSearchService::repository
     * @param object $repository GetOneRepositoryInterface
     */
    public function setRepository(GetOneRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
