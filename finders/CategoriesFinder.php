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
    private $storage;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            /*if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }*/
            
            if (empty($this->storage)) {
                $query = CategoriesModel::find();
                $query->select(['[[categories.id]]', '[[categories.name]]', '[[categories.seocode]]', '[[categories.active]]']);
                $query->with('subcategory');
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
