<?php

namespace app\queries;

use app\queries\AbstractInsertQueryCreator;

/**
 * Конструирует запрос к БД
 */
class CurrencyUpdateMainNullQueryCreator extends AbstractInsertQueryCreator
{
    /**
     * Инициирует создание UPDATE запроса
     * @return boolean
     */
    public function getInsertQuery()
    {
        try {
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            
            $this->_mapperObject->execute->update($this->_mapperObject->tableName, ['main'=>false]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
