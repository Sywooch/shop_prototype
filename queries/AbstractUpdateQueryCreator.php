<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;

abstract class AbstractUpdateQueryCreator extends AbstractBaseQueryCreator
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
            $this->getUpdateQuery();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание UPDATE запроса
     */
    public function getUpdateQuery()
    {
        try {
            $this->_mapperObject->query = 'UPDATE';
            $this->_mapperObject->query .= $this->addTableNameToInsert();
            $this->_mapperObject->query .= ' SET';
            $this->_mapperObject->query .= $this->addFieldsToUpdate();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
