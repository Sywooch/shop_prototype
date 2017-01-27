<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SubcategoryModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные подкатегории товаров из СУБД
 */
class SubcategorySeocodeFinder extends AbstractBaseFinder
{
    /**
     * @var string seocode категории
     */
    private $seocode;
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
            if (empty($this->storage)) {
                if (empty($this->seocode)) {
                    throw new ErrorException($this->emptyError('seocode'));
                }
                
                $query = SubcategoryModel::find();
                $query->select(['[[subcategory.id]]', '[[subcategory.name]]', '[[subcategory.seocode]]', '[[subcategory.active]]']);
                $query->where(['[[subcategory.seocode]]'=>$this->seocode]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает категорию свойству SubcategorySeocodeFinder::seocode
     * @param string $seocode
     */
    public function setSeocode(string $seocode)
    {
        try {
            $this->seocode = $seocode;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
