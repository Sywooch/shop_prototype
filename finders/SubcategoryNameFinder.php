<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SubcategoryModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные подкатегории товаров из СУБД
 */
class SubcategoryNameFinder extends AbstractBaseFinder
{
    /**
     * @var string имя категории
     */
    private $name;
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
            if (empty($this->name)) {
                throw new ErrorException($this->emptyError('name'));
            }
            
            if (empty($this->storage)) {
                $query = SubcategoryModel::find();
                $query->select(['[[subcategory.id]]', '[[subcategory.name]]', '[[subcategory.seocode]]', '[[subcategory.active]]']);
                $query->where(['[[subcategory.name]]'=>$this->name]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SubcategoryNameFinder::name
     * @param string $name
     */
    public function setName(string $name)
    {
        try {
            $this->name = $name;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
