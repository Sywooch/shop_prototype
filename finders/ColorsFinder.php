<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ColorsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class ColorsFinder extends AbstractBaseFinder
{
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
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
