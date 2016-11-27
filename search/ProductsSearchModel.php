<?php

namespace app\search;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    ProductsModel};
use app\queries\PaginationInterface;

class ProductsSearchModel extends Model
{
    use ExceptionsTrait;
    
    public $category;
    public $subcategory;
    public $page;
    
    private $collection;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function search()
    {
        try {
            $query = ProductsModel::find();
            $query->select(['[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
            $query->where(['[[products.active]]'=>true]);
            if (!empty($this->category)) {
                $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $query->where(['[[categories.seocode]]'=>$this->category]);
                if (!empty($this->subcategory)) {
                    $query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                    $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                }
            }
            
            $this->collection->pagination->pageSize = \Yii::$app->params['limit'];
            $this->collection->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
            
            $query->offset($this->collection->pagination->offset);
            $query->limit($this->collection->pagination->limit);
            $query->orderBy(['[[products.date]]'=>SORT_DESC]);
            
            $this->collection->pagination->configure($query);
            
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
}
