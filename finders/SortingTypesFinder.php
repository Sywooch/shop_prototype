<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает доступные категории товаров из СУБД
 */
class SortingTypesFinder extends AbstractBaseFinder
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
                $this->collection->addArray(['name'=>'SORT_ASC', 'value'=>\Yii::t('base', 'Sort ascending')]);
                $this->collection->addArray(['name'=>'SORT_DESC', 'value'=>\Yii::t('base', 'Sort descending')]);
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
