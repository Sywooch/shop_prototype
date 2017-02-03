<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SubcategoryModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные подкатегории товаров из СУБД
 */
class SubcategoryIdCategoryFinder extends AbstractBaseFinder
{
    /**
     * @var int id_category
     */
    private $id_category;
    /**
     * @var array массив загруженных SubcategoryModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id_category)) {
                throw new ErrorException($this->emptyError('id_category'));
            }
            
            if (empty($this->storage)) {
                
                
                $query = SubcategoryModel::find();
                $query->select(['[[subcategory.id]]', '[[subcategory.name]]']);
                $query->where(['[[subcategory.id_category]]'=>$this->id_category]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает категорию свойству SubcategoryIdCategoryFinder::id_category
     * @param int $id_category
     */
    public function setId_category(int $id_category)
    {
        try {
            $this->id_category = $id_category;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
