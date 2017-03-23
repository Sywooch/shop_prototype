<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ColorsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class ColorsProductFinder extends AbstractBaseFinder
{
    /**
     * @var int ID товара
     */
    private $id_product;
    /**
     * @var массив загруженных ColorsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                $query = ColorsModel::find();
                $query->select(['[[colors.id]]', '[[colors.color]]', '[[colors.hexcolor]]']);
                $query->distinct();
                $query->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
                $query->where(['[[products_colors.id_product]]'=>$this->id_product]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает подкатегорию свойству ColorsProductFinder::id_product
     * @param int $id_product
     */
    public function setId_product(int $id_product)
    {
        try {
            $this->id_product = $id_product;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
