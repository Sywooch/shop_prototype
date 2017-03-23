<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ColorsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные бренды из СУБД
 */
class ColorColorFinder extends AbstractBaseFinder
{
    /**
     * @var staring название цвета
     */
    private $color;
    /**
     * @var ColorsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->color)) {
                throw new ErrorException($this->emptyError('color'));
            }
            
            if (empty($this->storage)) {
                $query = ColorsModel::find();
                $query->select(['[[colors.id]]', '[[colors.color]]', '[[colors.hexcolor]]']);
                $query->where(['[[colors.color]]'=>$this->color]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ColorColorFinder::color
     * @param string $color
     */
    public function setColor(string $color)
    {
        try {
            $this->color = $color;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
