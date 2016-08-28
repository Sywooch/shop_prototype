<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;

abstract class AbstractDeleteQueryCreator extends AbstractBaseQueryCreator
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
            if (!$this->getDeleteQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание DELETE запроса
     * @return boolean
     */
    public function getDeleteQuery()
    {
        try {
            if (empty($this->_mapperObject->tableName)) {
                throw new ErrorException('Не задано имя таблицы!');
            }
            if (empty($this->_mapperObject->objectsArray)) {
                throw new ErrorException('Отсутствуют объекты для удаления!');
            }
            
            foreach ($this->_mapperObject->objectsArray as $object) {
                $reflectionProperty = (new \ReflectionClass($object))->getProperty($this->fieldWhere);
                $this->_mapperObject->params[] = $reflectionProperty->getValue($object);
            }
            
            $query = \Yii::$app->db->createCommand()->delete(
                $this->_mapperObject->tableName,
                [$this->fieldWhere=>$this->_mapperObject->params]
            );
            
            $this->_mapperObject->query = $query->getRawSql();
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
