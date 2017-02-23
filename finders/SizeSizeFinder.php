<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SizesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные бренды из СУБД
 */
class SizeSizeFinder extends AbstractBaseFinder
{
    /**
     * @var staring название цвета
     */
    private $size;
    /**
     * @var SizesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->size)) {
                throw new ErrorException($this->emptyError('size'));
            }
            
            if (empty($this->storage)) {
                $query = SizesModel::find();
                $query->select(['[[sizes.id]]', '[[sizes.size]]']);
                $query->where(['[[sizes.size]]'=>$this->size]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SizeSizeFinder::size
     * @param string $size
     */
    public function setSize(string $size)
    {
        try {
            $this->size = $size;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
