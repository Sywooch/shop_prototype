<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\BrandsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные бренды из СУБД
 */
class BrandBrandFinder extends AbstractBaseFinder
{
    /**
     * @var staring название бренда
     */
    private $brand;
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
            if (empty($this->brand)) {
                throw new ErrorException($this->emptyError('brand'));
            }
            
            if (empty($this->storage)) {
                $query = BrandsModel::find();
                $query->select(['[[brands.id]]', '[[brands.brand]]']);
                $query->where(['[[brands.brand]]'=>$this->brand]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение BrandBrandFinder::brand
     * @param string $brand
     */
    public function setBrand(string $brand)
    {
        try {
            $this->brand = $brand;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
