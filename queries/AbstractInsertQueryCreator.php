<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;

abstract class AbstractInsertQueryCreator extends AbstractBaseQueryCreator
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
            if (!$this->getInsertQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание INSERT запроса
     * @return boolean
     */
    public function getInsertQuery()
    {
        try {
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Не заданы поля!');
            }
            if (empty($this->_mapperObject->objectsArray)) {
                throw new ErrorException('Не задан массив объектов!');
            }
            
            foreach ($this->_mapperObject->objectsArray as $object) {
                $dataArray = array();
                foreach ($this->_mapperObject->fields as $field) {
                    $dataArray[] = $object->$field;
                }
                $this->_mapperObject->params[] = $dataArray;
            }
            
            $query = \Yii::$app->db->createCommand()->batchInsert(
                $this->_mapperObject->tableName, 
                $this->_mapperObject->fields, 
                $this->_mapperObject->params
            );
            
            $this->_mapperObject->query = $query->getRawSql();
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
