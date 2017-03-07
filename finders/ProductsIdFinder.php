<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает ProductsModel выбранных товаров из СУБД
 */
class ProductsIdFinder extends AbstractBaseFinder
{
    /**
     * @var array ID товаров
     */
    private $idArray;
    /**
     * @var ProductsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->idArray)) {
                throw new ErrorException($this->emptyError('idArray'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.date]]', '[[products.code]]', '[[products.name]]', '[[products.short_description]]', '[[products.description]]', '[[products.price]]', '[[products.images]]', '[[products.id_category]]', '[[products.id_subcategory]]', '[[products.id_brand]]', '[[products.active]]', '[[products.total_products]]', '[[products.seocode]]', '[[products.views]]']);
                $query->where(['[[products.id]]'=>$this->idArray]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ProductsIdFinder::idArray
     * @param array $idArray
     */
    public function setIdArray(array $idArray)
    {
        try {
            $this->idArray = $idArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
