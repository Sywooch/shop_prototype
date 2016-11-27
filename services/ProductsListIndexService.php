<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    ProductsModel};
use app\services\SearchServiceInterface;
use app\queries\PaginationInterface;

class ProductsListIndexService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
    public $searchModel;
    
    public function init()
    {
        try {
            parent::init();
            
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
           $collection = $this->searchModel->load($request);
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству ProductsListIndexService::collection
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
     * Присваивает PaginationInterface свойству ProductsListIndexService::pagination
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
