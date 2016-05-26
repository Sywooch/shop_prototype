<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\interfaces\VisitorInterface;

abstract class AbstractSeletcQueryCreator extends AbstractBaseQueryCreator implements VisitorInterface
{
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве, реализуя VisitorInterface
     * запускает процесс
     * @param $object
     */
    public function update($object)
    {
        try {
            $this->_mapperObject = $object;
            $this->getSelectQuery();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->addTableName();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
