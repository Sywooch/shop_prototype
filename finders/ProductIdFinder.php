<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductIdFinder extends AbstractBaseFinder
{
    /**
     * @var int ID товара
     */
    private $id;
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
            if (empty($this->id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.date]]', '[[products.code]]', '[[products.name]]', '[[products.short_description]]', '[[products.description]]', '[[products.price]]', '[[products.images]]', '[[products.id_category]]', '[[products.id_subcategory]]', '[[products.id_brand]]', '[[products.active]]', '[[products.total_products]]', '[[products.seocode]]', '[[products.views]]']);
                $query->where(['[[products.id]]'=>$this->id]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ID товара свойству ProductIdFinder::id
     * @param int $id
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
