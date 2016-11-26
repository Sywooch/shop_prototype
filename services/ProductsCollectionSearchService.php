<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use yii\db\Query;
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\models\CollectionInterface;
use app\services\SearchServiceInterface;
use app\filters\{JoinFilter,
    LimitFilter,
    OffsetFilter,
    SortingFilter,
    WhereFilter};
use app\queries\CriteriaInterface;

class ProductsCollectionSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object RepositoryInterface для поиска данных по запросу
     */
    private $repository;
    /**
     * @var object Query для построения запроса
     */
    private $query;
    /**
     * @var object CriteriaInterface
     */
    private $criteria;
    /**
     * @var object CollectionInterface
     */
    private $collection;
    /**
     * @var object PaginationInterface
     */
    private $pagination;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            if (empty($this->query)) {
                throw new ErrorException(ExceptionsTrait::emptyError('query'));
            }
            if (empty($this->criteria)) {
                throw new ErrorException(ExceptionsTrait::emptyError('query'));
            }
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            if (empty($this->pagination)) {
                throw new ErrorException(ExceptionsTrait::emptyError('pagination'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на поиск коллекции товаров
     * @param array $request
     * @return CollectionInterface
     */
    public function search($request): CollectionInterface
    {
        try {
            $this->criteria->setFilter(new WhereFilter(['condition'=>['[[products.active]]'=>true]]));
            if (!empty($category = $request[\Yii::$app->params['categoryKey']])) {
                $this->criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{categories}}', 'condition'=>'[[categories.id]]=[[products.id_category]]']]));
                $this->criteria->setFilter(new WhereFilter(['condition'=>['[[categories.seocode]]'=>$category]]));
                if (!empty($subcategory = $request[\Yii::$app->params['subcategoryKey']])) {
                    $this->criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{subcategory}}', 'condition'=>'[[subcategory.id]]=[[products.id_subcategory]]']]));
                    $this->criteria->setFilter(new WhereFilter(['condition'=>['[[subcategory.seocode]]'=>$subcategory]]));
                }
            }
            $this->pagination->pageSize = \Yii::$app->params['limit'];
            $this->pagination->page = !empty($page = $request[\Yii::$app->params['pagePointer']]) ? (int) $page - 1 : 0;
            $this->criteria->setFilter(new OffsetFilter(['condition'=>$this->pagination->offset]));
            $this->criteria->setFilter(new LimitFilter(['condition'=>$this->pagination->limit]));
            $this->criteria->setFilter(new SortingFilter(['condition'=>['field'=>'date', 'type'=>SORT_DESC]]));
            $this->criteria->apply($this->query);
            
            $this->pagination->configure($this->query);
            
            $this->collection->pagination = $this->pagination;
            
            $this->repository->query = $this->query;
            $this->repository->collection = $this->collection;
            
            $collection = $this->repository->getGroup();
            
            return $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству ProductsCollectionSearchService::repository
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
    
    /**
     * Присваивает Query свойству ProductsCollectionSearchService::query
     * @param object $query Query
     */
    public function setQuery(Query $query)
    {
        try {
            $this->query = $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CriteriaInterface свойству ProductsCollectionSearchService::criteria
     * @param object $criteria CriteriaInterface
     */
    public function setCriteria(CriteriaInterface $criteria)
    {
        try {
            $this->criteria = $criteria;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству ProductsCollectionSearchService::collection
     * @param object $collection CollectionInterface
     */
    public function setCollection(CollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PaginationInterface свойству ProductsCollectionSearchService::pagination
     * @param object $pagination PaginationInterface
     */
    public function setPagination(PaginationInterface $pagination)
    {
        try {
            $this->pagination = $pagination;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
