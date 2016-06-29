<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;
use yii\base\ErrorException;

abstract class AbstractUpdateQueryCreator extends AbstractBaseQueryCreator
{
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве, реализуя VisitorInterface
     * запускает процесс
     * @param $object
     * @return boolean
     */
    public function update(AbstractBaseMapper $object)
    {
        try {
            if (!parent::update($object)) {
                throw new ErrorException('Ошибка при сохранении объекта, для которого выполняются действия!');
            }
            if (!$this->getUpdateQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание UPDATE запроса
     * @return boolean
     */
    public function getUpdateQuery()
    {
        try {
            $this->_mapperObject->query = 'UPDATE';
            $name = $this->addTableNameToInsert();
            if (!is_string($name)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $name;
            $this->_mapperObject->query .= ' SET';
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
