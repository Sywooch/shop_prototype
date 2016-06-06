<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use yii\base\ErrorException;

/**
 * Реализует вставку строк данных в БД
 */
abstract class AbstractGroupInsertMapper extends AbstractInsertMapper
{
    /**
     * @var array массив ключей и значений для подстановки в строку запроса
     */
    public $insertData = array();
    
    /**
     * Создает группу записей в БД
     * @return boolean
     */
    public function setGroup()
    {
        try {
            $this->run();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return true;
    }
    
    /**
     * Формирует агрегированный массив данных для привязки к запросу
     */
    protected function getBindArray()
    {
        try {
            if (isset($this->objectsArray)) {
                return $this->insertData;
            } else {
                throw new ErrorException('Отсутсвуют данные для выполнения запроса!');
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
