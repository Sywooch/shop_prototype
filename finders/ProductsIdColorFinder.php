<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\ProductsModel;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsIdColorFinder extends AbstractBaseFinder
{
    /**
     * @var int ID цвета
     */
    private $id_color;
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
            if (empty($this->id_color)) {
                throw new ErrorException($this->emptyError('id_color'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->innerJoin('{{products_colors}}', '[[products.id]]=[[products_colors.id_product]]');
                $query->where(['[[products_colors.id_color]]'=>$this->id_color]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ProductsIdColorFinder::id_color
     * @param int $id_color
     */
    public function setId_color(int $id_color)
    {
        try {
            $this->id_color = $id_color;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
