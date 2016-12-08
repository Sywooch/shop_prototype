<?php

namespace app\collections;

use yii\base\ErrorException;
use app\collections\BaseCollection;

/**
 * Реализует интерфейс доступа к коллекции данных sphinx
 */
class SphinxCollection extends BaseCollection
{
    /**
     * Получает массивы строк из СУБД и добавляет их в коллекцию
     * @return $this
     */
    public function getArrays()
    {
        try {
            if (empty($this->query)) {
                throw new ErrorException($this->emptyError('query'));
            }
            
            if ($this->isEmpty() === true) {
                $arraysArray = $this->query->all();
                if (!empty($arraysArray)) {
                    foreach ($arraysArray as $array) {
                        $this->addArray($array);
                    }
                }
            }
            
            return $this;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
