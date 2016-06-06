<?php

namespace app\queries;

use app\queries\AbstractInsertQueryCreator;
use app\interfaces\VisitorInterface;

abstract class AbstractGroupInsertQueryCreator extends AbstractInsertQueryCreator
{
    /**
     * Инициирует создание INSERT запроса
     */
    public function getInsertQuery()
    {
        try {
            $this->_mapperObject->query = 'INSERT INTO';
            $this->_mapperObject->query .= $this->addTableNameToInsert();
            $this->_mapperObject->query .= $this->addFieldsToInsert();
            $this->_mapperObject->query .= $this->addGroupValuesToInsert();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
