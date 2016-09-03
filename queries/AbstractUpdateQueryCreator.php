<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;

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
                throw new ErrorException('Ошибка при сохранении объекта!');
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
            if (empty($this->_mapperObject->fields)) {
                throw new ErrorException('Не заданы поля!');
            }
            
            $sql = \Yii::$app->db->queryBuilder->batchInsert(
                $this->_mapperObject->tableName, 
                $this->_mapperObject->fields, 
                $this->_mapperObject->params
            );
            
            $onDuplicateArray = [];
            foreach ($this->_mapperObject->fields as $field) {
                if ($field != 'id') {
                    $onDuplicateArray[] = "[[{$field}]]=VALUES([[{$field}]])";
                }
            }
            
            $this->_mapperObject->execute = \Yii::$app->db->createCommand($sql . ' ON DUPLICATE KEY UPDATE ' . implode(', ', $onDuplicateArray));
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
