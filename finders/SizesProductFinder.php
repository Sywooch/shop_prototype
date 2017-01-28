<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SizesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class SizesProductFinder extends AbstractBaseFinder
{
    /**
     * @var int ID товара
     */
    private $id_product;
    /**
     * @var массив загруженных SizesModel
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
                $query = SizesModel::find();
                $query->select(['[[sizes.id]]', '[[sizes.size]]']);
                $query->distinct();
                $query->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
                $query->where(['[[products_sizes.id_product]]'=>$this->id_product]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает подкатегорию свойству SizesProductFinder::id_product
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
