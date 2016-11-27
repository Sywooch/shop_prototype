<?php

namespace app\search;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    ProductsModel};

class ProductsSearchModel extends Model
{
    use ExceptionsTrait;
    
    public $category;
    public $subcategory;
    public $page;
    
    private $collection;
    private $pagination;
    
    public function search()
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            $query = ProductsModel::find();
            $query->select(['[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
            $query->where(['[[products.active]]'=>true]);
            if (!empty($category)) {
                $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $query->where(['[[categories.seocode]]'=>$category]);
                if (!empty($subcategory)) {
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
     * Присваивает CollectionInterface свойству ProductsSearchModel::collection
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
     * Присваивает PaginationInterface свойству ProductsSearch::pagination
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
