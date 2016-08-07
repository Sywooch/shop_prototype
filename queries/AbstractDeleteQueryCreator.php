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
     * Инициирует создание INSERT запроса
     * @return boolean
     */
    public function getDeleteQuery()
    {
        try {
            $this->_mapperObject->query = 'DELETE';
            $name = $this->addTableName();
            if (!is_string($name)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $name;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
