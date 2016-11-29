<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    ProductsModel};
use app\services\SearchServiceInterface;
use app\queries\PaginationInterface;

class ProductsCollectionSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
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
    public function handle($request): CollectionInterface
    {
        try {
            $query = ProductsModel::find();
            $query->select(['[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
            $query->where(['[[products.active]]'=>true]);
            if (!empty($category = $request[\Yii::$app->params['categoryKey']])) {
                $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $query->where(['[[categories.seocode]]'=>$category]);
                if (!empty($subcategory = $request[\Yii::$app->params['subcategoryKey']])) {
                    $query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                    $query->andWhere(['[[subcategory.seocode]]'=>$subcategory]);
                }
            }
            
            $this->pagination->pageSize = \Yii::$app->params['limit'];
            $this->pagination->page = !empty($page = $request[\Yii::$app->params['pagePointer']]) ? (int) $page - 1 : 0;
            
            $query->offset($this->pagination->offset);
            $query->limit($this->pagination->limit);
            $query->orderBy(['[[products.date]]'=>SORT_DESC]);
            
            $this->pagination->configure($query);
            $this->collection->pagination = $this->pagination;
            
            $productsArray = $query->all();
            
            foreach ($productsArray as $object) {
                $this->collection->add($object);
            }
            
            return $this->collection;
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
