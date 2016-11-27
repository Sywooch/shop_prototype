<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\models\{CollectionInterface,
    BrandsModel};
use app\services\SearchServiceInterface;
use app\queries\PaginationInterface;

class BrandsFilterSearch extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
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
    
    /**
     * Обрабатывает запрос на поиск коллекции товаров
     * @param array $request
     * @return CollectionInterface
     */
    public function search($request): CollectionInterface
    {
        try {
            $query = BrandsModel::find();
            $query->select(['[[brands.id]]', '[[brands.brand]]']);
            $query->distinct();
            $query->innerJoin('{{products}}', '[[products.id_brand]]=[[brands.id]]');
            $query->where(['[[products.active]]'=>true]);
            
            if (!empty($category = \Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $query->innerJoin('{{categories}}', '[[products.id_category]]=[[categories.id]]');
                $query->andWhere(['[[categories.seocode]]'=>$category]);
                if (!empty($subcategory = \Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                    $query->innerJoin('{{subcategory}}', '[[products.id_subcategory]]=[[subcategory.id]]');
                    $query->andWhere(['[[subcategory.seocode]]'=>$subcategory]);
                }
            }
            
            $collection = $query->all();
            
            foreach ($collection as $object) {
                $this->collection->add($object);
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству BrandsFilterSearch::collection
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
