<?php

namespace app\factories;

use app\factories\AbstractBaseFactory;
use app\mappers\AbstractBaseMapper;
use yii\base\ErrorException;

/**
 * Конструирует массив объектов из массива строк БД
 */
abstract class AbstractGetObjectsFactory extends AbstractBaseFactory
{
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве,
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
            if (!$this->getObjects()) {
                throw new ErrorException('Ошибка при постороении объектов!');
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает на основе массива строк массив объектов
     * @return boolean
     */
    public function getObjects()
    {
        try {
            if (empty($this->_mapperObject->DbArray)) {
                return false;
            }
            
            if (empty($this->model)) {
                throw new ErrorException('Не задан объект класса модели для создания экземпляров!');
            }
                
            foreach ($this->_mapperObject->DbArray as $entry) {
                $model = clone $this->model;
                $model->attributes = $entry;
                $this->_mapperObject->objectsArray[] = $model;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
