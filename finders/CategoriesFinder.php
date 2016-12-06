<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CategoriesModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает доступные категории товаров из СУБД
 */
class CategoriesFinder extends AbstractBaseFinder
{
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $query = CategoriesModel::find();
                $query->select(['[[categories.id]]', '[[categories.name]]', '[[categories.seocode]]', '[[categories.active]]']);
                $query->with('subcategory');
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
