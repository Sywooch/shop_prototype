<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\ProductsModel;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductCodeFinder extends AbstractBaseFinder
{
    /**
     * @var string
     */
    private $code;
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
            if (empty($this->code)) {
                throw new ErrorException($this->emptyError('code'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]']);
                $query->where(['[[products.code]]'=>$this->code]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ProductCodeFinder::code
     * @param string $code
     */
    public function setCode(string $code)
    {
        try {
            $this->code = $code;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
