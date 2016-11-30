<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\models\CategoriesModel;
use app\finders\FinderInterface;
use app\collections\CollectionInterface;

class CategoriesFinder extends Model implements FinderInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
    private $collection;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException(ExceptionsTrait::emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = CategoriesModel::find();
                $query->with('subcategory');
                $categoriesArray = $query->all();
                
                if (!empty($categoriesArray)) {
                    foreach ($categoriesArray as $category) {
                        $this->collection->add($category);
                    }
                }
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству CategoriesFinder::collection
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
