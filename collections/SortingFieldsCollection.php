<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\BaseCollection;

/**
 * Реализует интерфейс доступа к данным коллекции сущностей
 */
class SortingFieldsCollection extends BaseCollection
{
    /**
     * Добавляет массив в коллекцию
     * @param array $array 
     */
    public function addArray(array $array)
    {
        try {
            if ($this->isEmpty() === false) {
                foreach ($this->items as $item) {
                    if ((string) $item['name'] === (string) $array['name']) {
                        return;
                    }
                }
            }
            
            $this->items[] = $array;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные поля сортировки по умолчанию
     * @return mixed
     */
    public function getDefault()
    {
        try {
            if ($this->isEmpty() === true) {
                throw new ErrorException($this->emptyError('items'));
            }
            
            foreach ($this->items as $item) {
                if ($item['name'] === 'date') {
                    return $item;
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
