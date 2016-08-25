<?php

namespace app\queries;

use app\queries\AbstractInsertQueryCreator;

/**
 * Конструирует запрос к БД
 */
abstract class AbstractTrueUpdateQueryCreator extends AbstractInsertQueryCreator
{
    /**
     * Инициирует создание UPDATE запроса
     * @return boolean
     */
    public function getInsertQuery()
    {
        try {
            $this->_mapperObject->query = 'UPDATE';
            
            $name = $this->addTableNameToInsert();
            if (!is_string($name)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $name;
            
            $fields = $this->addFieldsToUpdate();
            if (!is_string($fields)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $fields;
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
