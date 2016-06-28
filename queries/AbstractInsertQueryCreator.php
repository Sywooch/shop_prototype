<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;

abstract class AbstractInsertQueryCreator extends AbstractBaseQueryCreator
{
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве, реализуя VisitorInterface
     * запускает процесс
     * @param $object
     */
    public function update(AbstractBaseMapper $object)
    {
        try {
            parent::update($object);
            $this->getInsertQuery();
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
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
