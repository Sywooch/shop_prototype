<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\ProductsModel;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsIdSizeFinder extends AbstractBaseFinder
{
    /**
     * @var int ID цвета
     */
    private $id_size;
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
            if (empty($this->id_size)) {
                throw new ErrorException($this->emptyError('id_size'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]']);
                $query->innerJoin('{{products_sizes}}', '[[products.id]]=[[products_sizes.id_product]]');
                $query->where(['[[products_sizes.id_size]]'=>$this->id_size]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ProductsIdSizeFinder::id_size
     * @param int $id_size
     */
    public function setId_size(int $id_size)
    {
        try {
            $this->id_size = $id_size;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
