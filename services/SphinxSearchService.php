<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\models\CollectionInterface;
use app\services\SearchServiceInterface;
use app\filters\{FromFilter,
    LimitFilter,
    MatchFilter,
    OffsetFilter,
    SelectFilter,
    SortingFilter,
    WhereFilter};

class SphinxSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object RepositoryInterface для поиска данных по запросу
     */
    private $sphinxRepository;
    /**
     * @var object RepositoryInterface для поиска данных по запросу
     */
    private $productsRepository;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->sphinxRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('sphinxRepository'));
            }
            if (empty($this->productsRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('productsRepository'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на поиск списка товаров
     * @param object Request
     * @return CollectionInterface
     */
    public function search($request): CollectionInterface
    {
        try {
            if (empty($request[\Yii::$app->params['searchKey']])) {
                throw new ErrorException(ExceptionsTrait::emptyError('searchKey'));
            }
            
            $criteria = $this->sphinxRepository->criteria;
            $criteria->setFilter(new SelectFilter(['condition'=>['id']]));
            $criteria->setFilter(new FromFilter(['condition'=>'{{shop}}']));
            $criteria->setFilter(new MatchFilter(['condition'=>['fields'=>'[[@* :search]]', 'condition'=>['search'=>$request[\Yii::$app->params['searchKey']]]]]));
            $sphinxCollection = $this->sphinxRepository->getGroup();
            
            $criteria = $this->productsRepository->criteria;
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products.id]]'=>ArrayHelper::getColumn($sphinxCollection, 'id')]]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products.active]]'=>true]]));
            $pagination = $this->productsRepository->collection->pagination;
            $pagination->pageSize = \Yii::$app->params['limit'];
            $pagination->page = !empty($request[\Yii::$app->params['pagePointer']]) ? $request[\Yii::$app->params['pagePointer']] - 1 : 0;
            $criteria->setFilter(new OffsetFilter(['condition'=>$pagination->offset]));
            $criteria->setFilter(new LimitFilter(['condition'=>$pagination->limit]));
            $criteria->setFilter(new SortingFilter());
            $collection = $this->productsRepository->getGroup();
            
            return $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству SphinxSearchService::sphinxRepository
     * @param object $repository RepositoryInterface
     */
    public function setSphinxRepository(RepositoryInterface $repository)
    {
        try {
            $this->sphinxRepository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству SphinxSearchService::productsRepository
     * @param object $repository RepositoryInterface
     */
    public function setProductsRepository(RepositoryInterface $repository)
    {
        try {
            $this->productsRepository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
