<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\BrandsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные бренды из СУБД
 */
class BrandIdFinder extends AbstractBaseFinder
{
    /**
     * @var int ID бренда
     */
    private $id;
    /**
     * @var BrandsModel
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
                $query = BrandsModel::find();
                $query->select(['[[brands.id]]', '[[brands.brand]]']);
                $query->where(['[[brands.id]]'=>$this->id]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение BrandIdFinder::id
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
