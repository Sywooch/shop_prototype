<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;

abstract class AbstractSeletcQueryCreator extends AbstractBaseQueryCreator
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
            if (!$this->getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Не заданы поля!');
            }
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            
            $this->_query->select($this->_mapperObject->fields);
            
            $this->_query->from($this->_mapperObject->tableName);
            
            $this->_mapperObject->query = $this->_query->createCommand()->getRawSql();
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
