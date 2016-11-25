<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\models\ProductsModel;
use app\services\SearchServiceInterface;
use app\filters\WhereFilter;

class OneProductSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
     /**
     * @var object RepositoryInterface для поиска данных по запросу
     */
    private $repository;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на поиск 1 товара
     * @param object Request
     * @return ProductsModel
     */
    public function search($request): ProductsModel
    {
        try {
            if (empty($seocode = $request[\Yii::$app->params['productKey']])) {
                throw new ErrorException(ExceptionsTrait::emptyError('seocode'));
            }
            
            $criteria = $this->repository->criteria;
            $criteria->setFilter(new WhereFilter(['condition'=>['seocode'=>$seocode]]));
            $model = $this->repository->getOne();
            
            if (empty($model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
            
            return $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству ProductSearchService::repository
     * @param object $repository RepositoryInterface
     */
    public function setRepository(RepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
