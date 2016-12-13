<?php

namespace app\finders;

use app\models\CategoriesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные категории товаров из СУБД
 */
class CategoriesFinder extends AbstractBaseFinder
{
    /**
     * @var array массив загруженных CategoriesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
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
