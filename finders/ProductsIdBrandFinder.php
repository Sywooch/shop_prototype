<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\ProductsModel;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsIdBrandFinder extends AbstractBaseFinder
{
    /**
     * @var int ID бренда
     */
    private $id_brand;
    /**
     * @var array ProductsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->id_brand)) {
                throw new ErrorException($this->emptyError('id_brand'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->where(['[[products.id_brand]]'=>$this->id_brand]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ProductsIdBrandFinder::id_brand
     * @param int $id_brand
     */
    public function setId_brand(int $id_brand)
    {
        try {
            $this->id_brand = $id_brand;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
