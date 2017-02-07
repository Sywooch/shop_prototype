<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SubcategoryModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные подкатегории товаров из СУБД
 */
class SubcategoryIdFinder extends AbstractBaseFinder
{
    /**
     * @var int ID подкатегории
     */
    private $id;
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
            if (empty($this->id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->storage)) {
                $query = SubcategoryModel::find();
                $query->select(['[[subcategory.id]]', '[[subcategory.name]]', '[[subcategory.seocode]]', '[[subcategory.active]]']);
                $query->where(['[[subcategory.id]]'=>$this->id]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ID свойству SubcategoryIdFinder::id
     * @param string $id
     */
    public function setId(int $id)
    {
        try {
            $this->id = $id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
