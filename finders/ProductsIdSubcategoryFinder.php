<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\ProductsModel;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsIdSubcategoryFinder extends AbstractBaseFinder
{
    /**
     * @var int ID подкатегории
     */
    private $id_subcategory;
    /**
     * @var ProductsCollection
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id_subcategory)) {
                throw new ErrorException($this->emptyError('id_subcategory'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->where(['[[products.id_subcategory]]'=>$this->id_subcategory]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ProductsIdSubcategoryFinder::id_subcategory
     * @param int $id_subcategory
     */
    public function setId_subcategory(int $id_subcategory)
    {
        try {
            $this->id_subcategory = $id_subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
