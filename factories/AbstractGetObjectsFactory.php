<?php

namespace app\factories;

use app\factories\AbstractBaseFactory;
use app\interfaces\VisitorInterface;
use yii\base\ErrorException;

/**
 * Конструирует массив объектов из массива строк БД
 */
abstract class AbstractGetObjectsFactory extends AbstractBaseFactory implements VisitorInterface
{
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве,
     * запускает процесс
     * @param $object
     */
    public function update($object)
    {
        try {
            $this->_mapperObject = $object;
            $this->getObjects();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает на основе массива строк массив объектов
     */
    public function getObjects()
    {
        try {
            if (empty($this->_mapperObject->DbArray)) {
                throw new ErrorException('Отсутствуют данные для построения объектов!');
            }
            
            foreach ($this->_mapperObject->DbArray as $entry) {
                if (!isset($this->model) || !is_object($this->model)) {
                    throw new ErrorException('Не задан объект класса модели для создания экземпляров!');
                }
                $model = clone $this->model;
                $model->attributes = $entry;
                $this->_mapperObject->objectsArray[] = $model;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
