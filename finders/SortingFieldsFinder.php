<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает доступные категории товаров из СУБД
 */
class SortingFieldsFinder extends AbstractBaseFinder
{
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                $this->collection->addArray(['name'=>'date', 'value'=>\Yii::t('base', 'Sorting by date')]);
                $this->collection->addArray(['name'=>'price', 'value'=>\Yii::t('base', 'Sorting by price')]);
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
